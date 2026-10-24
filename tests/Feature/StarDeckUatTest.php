<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Loan;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StarDeckUatTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    private User $memberA;

    private User $memberB;

    protected function setUp(): void
    {
        parent::setUp();

        $this->seed();

        $this->admin = User::where('email', 'admin@stardeck.local')->firstOrFail();
        $this->memberA = User::where('email', 'aria@stardeck.local')->firstOrFail();
        $this->memberB = User::where('email', 'budi@stardeck.local')->firstOrFail();
    }

    protected function tearDown(): void
    {
        Carbon::setTestNow();

        parent::tearDown();
    }

    public function test_01_admin_and_member_can_login_to_their_role_dashboards(): void
    {
        $this->post('/login', [
            'email' => $this->admin->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($this->admin);
        $this->get('/')->assertOk()->assertSee('Ringkasan stasiun')->assertSee('Peminjaman Baru');

        $this->post('/logout')->assertRedirect('/login');
        $this->post('/login', [
            'email' => $this->memberA->email,
            'password' => 'password',
        ])->assertRedirect(route('dashboard'));

        $this->assertAuthenticatedAs($this->memberA);
        $this->get('/')
            ->assertOk()
            ->assertSee('Selamat kembali')
            ->assertDontSee('Data Astronot')
            ->assertDontSee('Peminjaman Baru');
    }

    public function test_02_invalid_password_and_unknown_email_share_the_same_login_error(): void
    {
        $wrongPassword = $this->from('/login')->post('/login', [
            'email' => $this->memberA->email,
            'password' => 'incorrect',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');
        $wrongPasswordMessage = session('errors')->get('email')[0];

        $unknownEmail = $this->from('/login')->post('/login', [
            'email' => 'unknown@stardeck.local',
            'password' => 'incorrect',
        ])->assertRedirect('/login')->assertSessionHasErrors('email');

        $this->assertSame($wrongPasswordMessage, session('errors')->get('email')[0]);
        $this->assertSame('Email atau password salah.', $wrongPasswordMessage);
    }

    public function test_03_guest_is_redirected_to_login_from_catalog(): void
    {
        $this->get('/catalog')->assertRedirect('/login');
    }

    public function test_04_member_cannot_open_admin_pages(): void
    {
        $this->actingAs($this->memberA)
            ->get(route('categories.index'))
            ->assertForbidden();

        $this->actingAs($this->memberA)
            ->get(route('loans.create'))
            ->assertForbidden();
    }

    public function test_05_logout_returns_user_to_login_and_ends_session(): void
    {
        $this->actingAs($this->memberA)
            ->post('/logout')
            ->assertRedirect('/login');

        $this->get('/')->assertRedirect('/login');
    }

    public function test_06_member_can_update_phone_and_station_module(): void
    {
        $this->actingAs($this->memberA)
            ->put('/profile', [
                'phone' => '081111222333',
                'rank_or_position' => 'Pilot Utama',
                'station_module' => 'Modul Uji',
                'address' => 'Koridor Uji',
            ])
            ->assertRedirect(route('profile.show'));

        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $this->memberA->id,
            'phone' => '081111222333',
            'station_module' => 'Modul Uji',
        ]);
    }

    public function test_07_invalid_phone_is_rejected_with_a_field_error(): void
    {
        foreach (['0812ABCD90', '12345678'] as $phone) {
            $this->actingAs($this->memberA)
                ->from('/profile/edit')
                ->put('/profile', [
                    'phone' => $phone,
                    'rank_or_position' => 'Pilot Utama',
                    'station_module' => 'Modul Alpha',
                ])
                ->assertRedirect('/profile/edit')
                ->assertSessionHasErrors('phone');
        }
    }

    public function test_08_catalog_search_for_cat_lists_both_catan_units(): void
    {
        $this->actingAs($this->memberA)
            ->get('/catalog?search=cat')
            ->assertOk()
            ->assertSee('SD-BG-001')
            ->assertSee('SD-BG-002')
            ->assertDontSee('SD-BG-003');
    }

    public function test_09_catalog_search_without_matches_shows_empty_state(): void
    {
        $this->actingAs($this->memberA)
            ->get('/catalog?search=zzzz')
            ->assertOk()
            ->assertSee('Unit tidak ditemukan');
    }

    public function test_10_category_name_must_be_unique(): void
    {
        $this->actingAs($this->admin)
            ->post('/categories', ['name' => 'Kategori UAT'])
            ->assertRedirect(route('categories.index'));

        $this->actingAs($this->admin)
            ->from('/categories/create')
            ->post('/categories', ['name' => 'Kategori UAT'])
            ->assertRedirect('/categories/create')
            ->assertSessionHasErrors('name');

        $this->assertSame(1, Category::where('name', 'Kategori UAT')->count());
    }

    public function test_11_category_referenced_by_a_unit_cannot_be_deleted(): void
    {
        $category = Category::where('name', 'Strategi')->firstOrFail();

        $this->actingAs($this->admin)
            ->delete(route('categories.destroy', $category))
            ->assertRedirect(route('categories.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('categories', ['id' => $category->id]);
    }

    public function test_12_unit_can_be_created_with_three_categories(): void
    {
        $categories = Category::whereIn('name', ['Strategi', 'Party', 'Kooperatif'])->get();

        $this->actingAs($this->admin)
            ->post('/units', [
                'name' => 'Unit UAT Tiga Kategori',
                'unit_code' => 'SD-BG-101',
                'type' => 'board_game',
                'condition' => 'Baik',
                'description' => 'Data uji UAT',
                'categories' => $categories->modelKeys(),
            ])
            ->assertRedirect(route('units.index'));

        $unit = Unit::where('unit_code', 'SD-BG-101')->firstOrFail();
        $this->assertCount(3, $unit->categories);
    }

    public function test_13_unit_code_must_be_unique_but_name_may_be_reused(): void
    {
        $category = Category::where('name', 'Strategi')->firstOrFail();

        $this->actingAs($this->admin)
            ->from('/units/create')
            ->post('/units', [
                'name' => 'Catan Baru',
                'unit_code' => 'SD-BG-001',
                'type' => 'board_game',
                'condition' => 'Baik',
                'categories' => [$category->id],
            ])
            ->assertRedirect('/units/create')
            ->assertSessionHasErrors('unit_code');

        $this->actingAs($this->admin)
            ->post('/units', [
                'name' => 'Catan',
                'unit_code' => 'SD-BG-099',
                'type' => 'board_game',
                'condition' => 'Baik',
                'categories' => [$category->id],
            ])
            ->assertRedirect(route('units.index'));

        $this->assertDatabaseHas('units', [
            'name' => 'Catan',
            'unit_code' => 'SD-BG-099',
        ]);
    }

    public function test_14_unit_with_active_loan_cannot_be_deleted(): void
    {
        $unit = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();
        $this->createLoan($this->memberA, $unit);

        $this->actingAs($this->admin)
            ->delete(route('units.destroy', $unit))
            ->assertRedirect(route('units.index'))
            ->assertSessionHas('error', 'Unit masih dipinjam. Tidak dapat dihapus.');

        $this->assertDatabaseHas('units', ['id' => $unit->id, 'deleted_at' => null]);
    }

    public function test_15_member_can_be_created_with_profile_but_duplicate_email_is_rejected(): void
    {
        $data = [
            'name' => 'Anggota UAT',
            'email' => 'uat@stardeck.local',
            'password' => 'password123',
            'phone' => '081234567899',
            'rank_or_position' => 'Operator UAT',
            'station_module' => 'Modul UAT',
            'address' => 'Koridor UAT',
        ];

        $this->actingAs($this->admin)
            ->post('/members', $data)
            ->assertRedirect(route('members.index'));

        $member = User::where('email', $data['email'])->firstOrFail();
        $this->assertDatabaseHas('member_profiles', [
            'user_id' => $member->id,
            'phone' => $data['phone'],
            'station_module' => $data['station_module'],
        ]);

        $this->actingAs($this->admin)
            ->from('/members/create')
            ->post('/members', $data)
            ->assertRedirect('/members/create')
            ->assertSessionHasErrors('email');

        $this->assertSame(1, User::where('email', $data['email'])->count());
    }

    public function test_16_member_with_active_loan_cannot_be_deleted(): void
    {
        $this->createLoan(
            $this->memberA,
            Unit::where('unit_code', 'SD-BG-001')->firstOrFail()
        );

        $this->actingAs($this->admin)
            ->delete(route('members.destroy', $this->memberA))
            ->assertRedirect(route('members.index'))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('users', [
            'id' => $this->memberA->id,
            'deleted_at' => null,
        ]);
    }

    public function test_17_borrowing_sets_due_date_and_unit_status(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-24 12:00:00'));
        $unit = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();

        $this->actingAs($this->admin)
            ->post('/loans', [
                'member_id' => $this->memberA->id,
                'unit_id' => $unit->id,
            ])
            ->assertRedirect(route('loans.active'));

        $loan = Loan::where('member_id', $this->memberA->id)->firstOrFail();
        $this->assertSame('2026-10-24', $loan->borrowed_at->toDateString());
        $this->assertSame('2026-10-29', $loan->due_date->toDateString());
        $this->assertSame('borrowed', $loan->status);
        $this->assertSame('borrowed', $unit->fresh()->status);
    }

    public function test_18_third_active_borrowing_is_rejected(): void
    {
        $units = Unit::whereIn('unit_code', ['SD-BG-001', 'SD-BG-002', 'SD-BG-003'])
            ->orderBy('unit_code')
            ->get();

        $this->createLoan($this->memberA, $units[0]);
        $this->createLoan($this->memberA, $units[1]);

        $this->actingAs($this->admin)
            ->from('/loans/create')
            ->post('/loans', [
                'member_id' => $this->memberA->id,
                'unit_id' => $units[2]->id,
            ])
            ->assertRedirect('/loans/create')
            ->assertSessionHas('error', 'Anggota sudah memiliki dua pinjaman aktif.');

        $this->assertSame(2, Loan::where('member_id', $this->memberA->id)->count());
    }

    public function test_19_borrowed_unit_cannot_be_borrowed_again(): void
    {
        $unit = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();
        $this->createLoan($this->memberA, $unit);

        $this->actingAs($this->admin)
            ->from('/loans/create')
            ->post('/loans', [
                'member_id' => $this->memberB->id,
                'unit_id' => $unit->id,
            ])
            ->assertRedirect('/loans/create')
            ->assertSessionHas('error', 'Unit sedang dipinjam oleh anggota lain.');

        $this->assertSame(1, Loan::where('unit_id', $unit->id)->count());
    }

    public function test_20_member_cannot_access_borrow_or_return_features(): void
    {
        $activeLoan = $this->createLoan(
            $this->memberA,
            Unit::where('unit_code', 'SD-BG-001')->firstOrFail()
        );

        $dashboard = $this->actingAs($this->memberA)->get('/');
        $dashboard->assertOk()
            ->assertDontSee('Peminjaman Baru')
            ->assertDontSee('Data Astronot');

        $this->get('/loans/create')->assertForbidden();
        $this->get(route('loans.return', $activeLoan))->assertForbidden();
        $this->get('/my-loans')->assertOk();
    }

    public function test_21_return_on_due_date_has_no_fine_and_releases_unit(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-24 12:00:00'));
        $unit = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();
        $loan = $this->createLoan($this->memberA, $unit, dueDate: '2026-10-24');

        $this->actingAs($this->admin)
            ->post(route('loans.process-return', $loan))
            ->assertRedirect(route('loans.active'));

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned',
            'late_days' => 0,
            'fine_amount' => 0,
        ]);
        $this->assertSame('2026-10-24', $loan->fresh()->returned_at->toDateString());
        $this->assertSame('available', $unit->fresh()->status);
    }

    public function test_22_return_eight_days_after_borrowing_calculates_three_days_fine(): void
    {
        Carbon::setTestNow(Carbon::parse('2026-10-24 12:00:00'));
        $unit = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();
        $loan = $this->createLoan($this->memberA, $unit, borrowedAt: '2026-10-16');

        $this->actingAs($this->admin)
            ->post(route('loans.process-return', $loan))
            ->assertRedirect(route('loans.active'))
            ->assertSessionHas('success', 'Pengembalian berhasil. Keterlambatan: 3 hari. Denda: Rp15.000.');

        $this->assertDatabaseHas('loans', [
            'id' => $loan->id,
            'status' => 'returned',
            'late_days' => 3,
            'fine_amount' => 15000,
        ]);
        $this->assertSame('available', $unit->fresh()->status);
    }

    public function test_23_admin_sees_all_active_loans_and_members_only_see_their_own(): void
    {
        $unitA = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();
        $unitB = Unit::where('unit_code', 'SD-BG-002')->firstOrFail();
        $this->createLoan($this->memberA, $unitA);
        $this->createLoan($this->memberB, $unitB);

        $this->actingAs($this->admin)
            ->get('/loans/active')
            ->assertOk()
            ->assertSee('SD-BG-001')
            ->assertSee('SD-BG-002');

        $this->actingAs($this->memberA)
            ->get('/my-loans')
            ->assertOk()
            ->assertSee('SD-BG-001')
            ->assertDontSee('SD-BG-002');

        $this->actingAs($this->memberB)
            ->get('/my-loans')
            ->assertOk()
            ->assertSee('SD-BG-002')
            ->assertDontSee('SD-BG-001');
    }

    public function test_24_admin_can_view_member_history_and_open_printable_report(): void
    {
        $unit = Unit::where('unit_code', 'SD-BG-001')->firstOrFail();
        $loan = $this->createLoan(
            $this->memberA,
            $unit,
            status: 'returned',
            returnedAt: Carbon::today()->toDateString()
        );

        $this->actingAs($this->admin)
            ->get(route('loans.history', $this->memberA))
            ->assertOk()
            ->assertSee('Riwayat Pinjaman: '.$this->memberA->name)
            ->assertSee('SD-BG-001')
            ->assertSee(route('loans.print-history', $this->memberA));

        $this->get(route('loans.print-history', $this->memberA))
            ->assertOk()
            ->assertSee('Laporan Riwayat Peminjaman')
            ->assertSee('Cetak Dokumen Sekarang')
            ->assertSee('SD-BG-001')
            ->assertSee('Catan');

        $this->assertDatabaseHas('loans', ['id' => $loan->id, 'status' => 'returned']);
    }

    private function createLoan(
        User $member,
        Unit $unit,
        ?string $borrowedAt = null,
        ?string $dueDate = null,
        string $status = 'borrowed',
        ?string $returnedAt = null,
    ): Loan {
        $borrowedAt ??= Carbon::today()->toDateString();
        $dueDate ??= Carbon::parse($borrowedAt)->addDays(5)->toDateString();

        if ($status === 'borrowed') {
            $unit->update(['status' => 'borrowed']);
        }

        return Loan::create([
            'member_id' => $member->id,
            'unit_id' => $unit->id,
            'processed_by' => $this->admin->id,
            'borrowed_at' => $borrowedAt,
            'due_date' => $dueDate,
            'returned_at' => $returnedAt,
            'status' => $status,
            'late_days' => 0,
            'fine_amount' => 0,
        ]);
    }
}
