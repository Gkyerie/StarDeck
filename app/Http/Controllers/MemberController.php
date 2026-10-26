<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreMemberRequest;
use App\Http\Requests\UpdateMemberRequest;
use App\Http\Requests\UpdateProfileRequest;
use App\Models\MemberProfile;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MemberController extends Controller
{
    public function __construct(
        private readonly LoanService $loanService
    ) {}

    /**
     * Admin: list all members.
     */
    public function index(): View
    {
        $members = User::where('role', 'member')
            ->with('memberProfile')
            ->withCount(['loans as active_loans_count' => function ($q) {
                $q->where('status', 'borrowed');
            }])
            ->orderBy('name')
            ->paginate(15);

        return view('members.index', compact('members'));
    }

    /**
     * Admin: show member detail.
     */
    public function show(int $member): View
    {
        $member = User::where('role', 'member')
            ->with('memberProfile')
            ->findOrFail($member);

        return view('members.show', compact('member'));
    }

    /**
     * Admin: create form.
     */
    public function create(): View
    {
        return view('members.create');
    }

    /**
     * Admin: store new member with profile in transaction.
     */
    public function store(StoreMemberRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        DB::transaction(function () use ($validated, $request) {
            $user = User::create([
                'name' => $validated['name'],
                'email' => $validated['email'],
                'password' => $validated['password'],
                'role' => 'member', // Always member, never trust form
            ]);

            $profileData = [
                'user_id' => $user->id,
                'phone' => $validated['phone'],
                'rank_or_position' => $validated['rank_or_position'],
                'station_module' => $validated['station_module'],
                'address' => $validated['address'] ?? null,
            ];

            if ($request->hasFile('photo')) {
                $profileData['photo'] = $request->file('photo')
                    ->store('profiles', 'public');
            }

            MemberProfile::create($profileData);
        });

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil ditambahkan.');
    }

    /**
     * Admin: edit form.
     */
    public function edit(int $member): View
    {
        $member = User::where('role', 'member')
            ->with('memberProfile')
            ->findOrFail($member);

        return view('members.edit', compact('member'));
    }

    /**
     * Admin: update member and profile.
     */
    public function update(UpdateMemberRequest $request, int $member): RedirectResponse
    {
        $member = User::where('role', 'member')->findOrFail($member);
        $validated = $request->validated();

        DB::transaction(function () use ($member, $validated, $request) {
            $userData = [
                'name' => $validated['name'],
                'email' => $validated['email'],
            ];

            // Only update password if provided
            if (! empty($validated['password'])) {
                $userData['password'] = $validated['password'];
            }

            $member->update($userData);

            $profileData = [
                'phone' => $validated['phone'],
                'rank_or_position' => $validated['rank_or_position'],
                'station_module' => $validated['station_module'],
                'address' => $validated['address'] ?? null,
            ];

            if ($request->hasFile('photo')) {
                // Delete old photo if exists
                if ($member->memberProfile && $member->memberProfile->photo) {
                    Storage::disk('public')->delete($member->memberProfile->photo);
                }
                $profileData['photo'] = $request->file('photo')
                    ->store('profiles', 'public');
            }

            $member->memberProfile()->updateOrCreate(
                ['user_id' => $member->id],
                $profileData
            );
        });

        return redirect()->route('members.index')
            ->with('success', 'Anggota berhasil diperbarui.');
    }

    /**
     * Admin: soft delete member.
     */
    public function destroy(int $member): RedirectResponse
    {
        try {
            $this->loanService->deleteMember($member);

            return redirect()->route('members.index')
                ->with('success', 'Anggota berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('members.index')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Member: view own profile.
     */
    public function myProfile(Request $request): View
    {
        $user = $request->user();
        $profile = $user->memberProfile;

        return view('profile.show', compact('user', 'profile'));
    }

    /**
     * Member: edit own profile form.
     */
    public function editMyProfile(Request $request): View
    {
        $user = $request->user();
        $profile = $user->memberProfile;

        return view('profile.edit', compact('user', 'profile'));
    }

    /**
     * Member: update own profile.
     */
    public function updateMyProfile(UpdateProfileRequest $request): RedirectResponse
    {
        $user = $request->user();
        $validated = $request->validated();

        $profileData = [
            'phone' => $validated['phone'],
            'rank_or_position' => $validated['rank_or_position'],
            'station_module' => $validated['station_module'],
            'address' => $validated['address'] ?? null,
        ];

        if ($request->hasFile('photo')) {
            if ($user->memberProfile && $user->memberProfile->photo) {
                Storage::disk('public')->delete($user->memberProfile->photo);
            }
            $profileData['photo'] = $request->file('photo')
                ->store('profiles', 'public');
        }

        $user->memberProfile()->update($profileData);

        return redirect()->route('profile.show')
            ->with('success', 'Profil berhasil diperbarui.');
    }
}
