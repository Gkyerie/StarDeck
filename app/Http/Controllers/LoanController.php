<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Unit;
use App\Models\User;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class LoanController extends Controller
{
    public function __construct(
        private readonly LoanService $loanService
    ) {}

    /**
     * Admin: show borrowing form.
     */
    public function create(): View
    {
        $members = User::where('role', 'member')
            ->whereNull('deleted_at')
            ->has('memberProfile')
            ->orderBy('name')
            ->get();

        $units = Unit::where('status', 'available')
            ->whereNull('deleted_at')
            ->orderBy('unit_code')
            ->get();

        return view('loans.create', compact('members', 'units'));
    }

    /**
     * Admin: process borrowing.
     */
    public function store(Request $request): RedirectResponse
    {
        $request->validate([
            'member_id' => ['required', 'integer', 'exists:users,id'],
            'unit_id' => ['required', 'integer', 'exists:units,id'],
        ], [
            'member_id.required' => 'Anggota wajib dipilih.',
            'member_id.exists' => 'Anggota tidak ditemukan.',
            'unit_id.required' => 'Unit wajib dipilih.',
            'unit_id.exists' => 'Unit tidak ditemukan.',
        ]);

        try {
            $this->loanService->borrow(
                (int) $request->input('member_id'),
                (int) $request->input('unit_id'),
                $request->user()->id
            );

            return redirect()->route('loans.active')
                ->with('success', 'Peminjaman berhasil dicatat.');
        } catch (\Exception $e) {
            return back()->withInput()
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: list all active loans.
     */
    public function active(): View
    {
        $loans = Loan::with(['member', 'unit', 'processedBy'])
            ->where('status', 'borrowed')
            ->orderByDesc('id')
            ->paginate(15);

        return view('loans.active', compact('loans'));
    }

    /**
     * Member: list own loans.
     */
    public function myLoans(Request $request): View
    {
        $loans = $request->user()->loans()
            ->with('unit')
            ->orderByDesc('id')
            ->paginate(15);

        return view('loans.my-loans', compact('loans'));
    }

    /**
     * Admin: show return confirmation.
     */
    public function showReturn(int $loan): View
    {
        $loan = Loan::with(['member', 'unit', 'processedBy'])
            ->where('status', 'borrowed')
            ->findOrFail($loan);

        return view('loans.return', compact('loan'));
    }

    /**
     * Admin: process return.
     */
    public function processReturn(int $loan): RedirectResponse
    {
        try {
            $returned = $this->loanService->returnLoan($loan);

            return redirect()->route('loans.active')
                ->with('success', sprintf(
                    'Pengembalian berhasil. Keterlambatan: %d hari. Denda: Rp%s.',
                    $returned->late_days,
                    number_format($returned->fine_amount, 0, ',', '.')
                ));
        } catch (\Exception $e) {
            return redirect()->route('loans.active')
                ->with('error', $e->getMessage());
        }
    }

    /**
     * Admin: member loan history.
     */
    public function history(int $member): View
    {
        $member = User::withTrashed()->findOrFail($member);
        $loans = Loan::with(['unit', 'processedBy'])
            ->where('member_id', $member->id)
            ->orderByDesc('id')
            ->paginate(20);

        return view('loans.history', compact('member', 'loans'));
    }

    /**
     * Admin: printable history page.
     */
    public function printHistory(int $member): View
    {
        $member = User::withTrashed()->findOrFail($member);
        $loans = Loan::with(['unit', 'processedBy'])
            ->where('member_id', $member->id)
            ->orderByDesc('id')
            ->get();

        return view('loans.print-history', compact('member', 'loans'));
    }
}
