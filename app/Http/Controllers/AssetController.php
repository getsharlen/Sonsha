<?php

namespace App\Http\Controllers;

use App\Models\ActivityLog;
use App\Models\Asset;
use App\Models\Category;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class AssetController extends Controller
{
    public function index(): View
    {
        $assets = Asset::with('category')->latest()->paginate(8);
        $categories = Category::orderBy('name')->get();

        return view('assets.index', compact('assets', 'categories'));
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'stock_total' => ['required', 'integer', 'min:0'],
            'stock_available' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:baik,rusak,maintenance'],
            'rent_fee' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ]);

        $asset = Asset::create([
            'category_id' => $data['category_id'],
            'code' => 'AST-'.Str::upper(Str::random(6)),
            'name' => $data['name'],
            'brand' => $data['brand'] ?? null,
            'stock_total' => $data['stock_total'],
            'stock_available' => $data['stock_available'],
            'condition' => $data['condition'],
            'rent_fee' => $data['rent_fee'],
            'description' => $data['description'] ?? null,
            'image_url' => $data['image_url'] ?? null,
        ]);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'module' => 'asset',
            'action' => 'create',
            'description' => 'Data alat baru ditambahkan.',
            'payload' => ['asset_id' => $asset->id],
        ]);

        return back()->with('status', 'Alat berhasil ditambahkan.');
    }

    public function update(Request $request, Asset $asset): RedirectResponse
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'brand' => ['nullable', 'string', 'max:255'],
            'stock_total' => ['required', 'integer', 'min:0'],
            'stock_available' => ['required', 'integer', 'min:0'],
            'condition' => ['required', 'in:baik,rusak,maintenance'],
            'rent_fee' => ['required', 'numeric', 'min:0'],
            'description' => ['nullable', 'string'],
            'image_url' => ['nullable', 'url'],
        ]);

        $asset->update($data);

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'module' => 'asset',
            'action' => 'update',
            'description' => 'Data alat diperbarui.',
            'payload' => ['asset_id' => $asset->id],
        ]);

        return back()->with('status', 'Alat berhasil diperbarui.');
    }

    public function destroy(Request $request, Asset $asset): RedirectResponse
    {
        if ($asset->items()->exists()) {
            return back()->with('status', 'Alat tidak bisa dihapus karena sudah memiliki histori peminjaman.');
        }

        ActivityLog::create([
            'user_id' => $request->user()->id,
            'module' => 'asset',
            'action' => 'delete',
            'description' => 'Data alat dihapus.',
            'payload' => ['asset_id' => $asset->id],
        ]);

        $asset->delete();

        return back()->with('status', 'Alat dihapus.');
    }
}