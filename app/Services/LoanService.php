<?php

namespace App\Services;

use App\Models\Loan;
use App\Models\Unit;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class LoanService
{
    /**
     * Process a borrowing transaction.
     * Lock order: member → unit → loan check.
     *
     * @throws \Exception
     */
    public function borrow(int $memberId, int $unitId, int $adminId): Loan
    {
        return DB::transaction(function () use ($memberId, $unitId, $adminId) {
            // 1. Lock member row, validate
            $member = User::lockForUpdate()->find($memberId);

            if (! $member) {
                throw new \Exception('Anggota tidak ditemukan.');
            }
            if ($member->trashed()) {
                throw new \Exception('Anggota sudah dinonaktifkan.');
            }
            if ($member->role !== 'member') {
                throw new \Exception('Pengguna bukan anggota.');
            }
            if (! $member->memberProfile) {
                throw new \Exception('Anggota belum memiliki profil lengkap.');
            }

            // 2. Lock unit row, validate
            $unit = Unit::lockForUpdate()->find($unitId);

            if (! $unit) {
                throw new \Exception('Unit tidak ditemukan.');
            }
            if ($unit->trashed()) {
                throw new \Exception('Unit sudah dinonaktifkan.');
            }
            if ($unit->status !== 'available') {
                throw new \Exception('Unit sedang dipinjam.');
            }

            // 3. Recheck active loans after lock
            $activeLoans = Loan::where('member_id', $memberId)
                ->where('status', 'borrowed')
                ->count();

            if ($activeLoans >= 2) {
                throw new \Exception('Anggota sudah memiliki dua pinjaman aktif.');
            }

            // Check no active loan for this unit
            $unitActiveLoan = Loan::where('unit_id', $unitId)
                ->where('status', 'borrowed')
                ->exists();

            if ($unitActiveLoan) {
                throw new \Exception('Unit sedang dipinjam oleh anggota lain.');
            }

            // 4. Create loan and update unit status
            $today = Carbon::today();
            $dueDate = $today->copy()->addDays(5);

            $loan = Loan::create([
                'member_id' => $memberId,
                'unit_id' => $unitId,
                'processed_by' => $adminId,
                'borrowed_at' => $today,
                'due_date' => $dueDate,
                'returned_at' => null,
                'status' => 'borrowed',
                'late_days' => 0,
                'fine_amount' => 0,
            ]);

            $unit->status = 'borrowed';
            $unit->save();

            return $loan;
        });
    }

    /**
     * Process a return transaction.
     * Lock order: member → unit → loan.
     *
     * @throws \Exception
     */
    public function returnLoan(int $loanId): Loan
    {
        return DB::transaction(function () use ($loanId) {
            // Read loan first to get member_id and unit_id
            $loan = Loan::find($loanId);

            if (! $loan) {
                throw new \Exception('Pinjaman tidak ditemukan.');
            }

            // Lock order: member → unit, then re-read loan
            $member = User::withTrashed()->lockForUpdate()->find($loan->member_id);
            $unit = Unit::withTrashed()->lockForUpdate()->find($loan->unit_id);

            // Re-read and lock loan
            $loan = Loan::lockForUpdate()->find($loanId);

            if (! $loan || $loan->status !== 'borrowed') {
                throw new \Exception('Pinjaman sudah dikembalikan atau tidak valid.');
            }

            // Calculate return values
            $today = Carbon::today();

            // returned_at must not be before borrowed_at
            if ($today->lt($loan->borrowed_at)) {
                throw new \Exception('Tanggal kembali tidak boleh sebelum tanggal pinjam.');
            }

            $lateDays = max(0, (int) $today->diffInDays($loan->due_date, false) * -1);
            $fineAmount = $lateDays * 5000;

            // Update loan
            $loan->returned_at = $today;
            $loan->status = 'returned';
            $loan->late_days = $lateDays;
            $loan->fine_amount = $fineAmount;
            $loan->save();

            // Update unit status
            if ($unit) {
                $unit->status = 'available';
                $unit->save();
            }

            return $loan;
        });
    }

    /**
     * Soft-delete a member (lock member, check active loans).
     *
     * @throws \Exception
     */
    public function deleteMember(int $memberId): void
    {
        DB::transaction(function () use ($memberId) {
            $member = User::lockForUpdate()->find($memberId);

            if (! $member) {
                throw new \Exception('Anggota tidak ditemukan.');
            }

            $activeLoans = Loan::where('member_id', $memberId)
                ->where('status', 'borrowed')
                ->exists();

            if ($activeLoans) {
                throw new \Exception('Anggota masih memiliki pinjaman aktif. Tidak dapat dihapus.');
            }

            $member->delete(); // soft delete
        });
    }

    /**
     * Soft-delete a unit (lock unit, check active loans).
     *
     * @throws \Exception
     */
    public function deleteUnit(int $unitId): void
    {
        DB::transaction(function () use ($unitId) {
            $unit = Unit::lockForUpdate()->find($unitId);

            if (! $unit) {
                throw new \Exception('Unit tidak ditemukan.');
            }

            $activeLoans = Loan::where('unit_id', $unitId)
                ->where('status', 'borrowed')
                ->exists();

            if ($activeLoans) {
                throw new \Exception('Unit masih dipinjam. Tidak dapat dihapus.');
            }

            $unit->delete(); // soft delete
        });
    }
}
