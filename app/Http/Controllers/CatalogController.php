<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CatalogController extends Controller
{
    public function index(Request $request): View
    {
        $query = Unit::with('categories');

        if ($search = $request->input('search')) {
            $query->where('name', 'like', '%' . $search . '%');
        }

        if ($categoryId = $request->input('category')) {
            $query->whereHas('categories', function ($q) use ($categoryId) {
                $q->where('categories.id', $categoryId);
            });
        }

        $units = $query->orderBy('unit_code')->paginate(15)->withQueryString();
        $categories = Category::orderBy('name')->get();

        return view('catalog.index', compact('units', 'categories'));
    }

    public function show(int $unit): View
    {
        $unit = Unit::with('categories')->findOrFail($unit);
        return view('catalog.show', compact('unit'));
    }
}
