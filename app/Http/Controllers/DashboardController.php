<?php

namespace App\Http\Controllers;

use App\Models\Loan;
use App\Models\Unit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();

        if ($user->isAdmin()) {
            return $this->adminDashboard();
        }

        return $this->memberDashboard($user);
    }

    private function adminDashboard(): View
    {
        $totalUnits = Unit::count();
        $availableUnits = Unit::where('status', 'available')->count();
        $borrowedUnits = Unit::where('status', 'borrowed')->count();
        $totalMembers = User::where('role', 'member')->count();
        $activeLoans = Loan::where('status', 'borrowed')->count();
        $overdueLoans = Loan::where('status', 'borrowed')
            ->where('due_date', '<', today())
            ->count();

        $recentLoans = Loan::with(['member', 'unit', 'processedBy'])
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard.admin', compact(
            'totalUnits',
            'availableUnits',
            'borrowedUnits',
            'totalMembers',
            'activeLoans',
            'overdueLoans',
            'recentLoans'
        ));
    }

    private function memberDashboard(User $user): View
    {
        $activeLoans = $user->loans()
            ->with('unit')
            ->where('status', 'borrowed')
            ->get();

        $recentLoans = $user->loans()
            ->with('unit')
            ->orderByDesc('id')
            ->limit(10)
            ->get();

        return view('dashboard.member', compact('activeLoans', 'recentLoans'));
    }
}
