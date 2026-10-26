<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUnitRequest;
use App\Http\Requests\UpdateUnitRequest;
use App\Models\Category;
use App\Models\Unit;
use App\Services\LoanService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\View\View;

class UnitController extends Controller
{
    public function __construct(
        private readonly LoanService $loanService
    ) {}

    public function index(Request $request): View
    {
        $query = Unit::with('categories');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%'.$search.'%');
        }

        if ($categoryId = $request->input('category')) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        if ($status = $request->input('status')) {
            $query->where('status', $status);
        }

        $units = $query->orderBy('unit_code')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('units.index', compact('units', 'categories'));
    }

    public function show(int $unit): View
    {
        $unit = Unit::with('categories')->findOrFail($unit);

        return view('units.show', compact('unit'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('units.create', compact('categories'));
    }

    public function store(StoreUnitRequest $request): RedirectResponse
    {
        $validated = $request->validated();
        $categoryIds = $validated['categories'];
        unset($validated['categories']);

        DB::transaction(function () use ($validated, $categoryIds) {
            $unit = Unit::create($validated);
            $unit->categories()->attach($categoryIds);
        });

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil ditambahkan.');
    }

    public function edit(int $unit): View
    {
        $unit = Unit::with('categories')->findOrFail($unit);
        $categories = Category::orderBy('name')->get();

        return view('units.edit', compact('unit', 'categories'));
    }

    public function update(UpdateUnitRequest $request, int $unit): RedirectResponse
    {
        $unit = Unit::findOrFail($unit);
        $validated = $request->validated();
        $categoryIds = $validated['categories'];
        unset($validated['categories']);

        DB::transaction(function () use ($unit, $validated, $categoryIds) {
            $unit->update($validated);
            $unit->categories()->sync($categoryIds);
        });

        return redirect()->route('units.index')
            ->with('success', 'Unit berhasil diperbarui.');
    }

    public function destroy(int $unit): RedirectResponse
    {
        try {
            $this->loanService->deleteUnit($unit);

            return redirect()->route('units.index')
                ->with('success', 'Unit berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->route('units.index')
                ->with('error', $e->getMessage());
        }
    }
}
