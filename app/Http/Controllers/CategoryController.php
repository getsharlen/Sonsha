<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CategoryController extends Controller
{
    public function index(): View
    {
        $categories = Category::withCount('assets')->latest()->get();

        return view('categories.index', compact('categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category = Category::create([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
            'description' => $data['description'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'module' => 'category',
            'action' => 'create',
            'description' => 'Kategori baru ditambahkan.',
            'payload' => ['category_id' => $category->id],
        ]);

        return back()->with('status', 'Kategori berhasil ditambahkan.');
    }

    public function update(Request $request, Category $category): RedirectResponse
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $category->update([
            'name' => $data['name'],
            'slug' => Str::slug($data['name']).'-'.Str::lower(Str::random(4)),
            'description' => $data['description'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'module' => 'category',
            'action' => 'update',
            'description' => 'Kategori diperbarui.',
            'payload' => ['category_id' => $category->id],
        ]);

        return back()->with('status', 'Kategori diperbarui.');
    }

    public function destroy(Request $request, Category $category): RedirectResponse
    {
        if ($category->assets()->exists()) {
            return back()->with('status', 'Kategori tidak bisa dihapus karena masih memiliki data alat.');
        }

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'module' => 'category',
            'action' => 'delete',
            'description' => 'Kategori dihapus.',
            'payload' => ['category_id' => $category->id],
        ]);

        $category->delete();

        return back()->with('status', 'Kategori dihapus.');
    }
}