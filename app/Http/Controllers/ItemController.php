<?php

namespace App\Http\Controllers;

use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $items = Item::latest()->paginate(10);
        $pageTitle = 'Halaman Item';
        return view('items.index', compact('items', 'pageTitle'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('items.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'kode' => 'required|unique:items',
                'nama' => 'required',
                'harga' => 'required',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            if ($request->hasFile('gambar')) {
                $validated['gambar'] = $request->file('gambar')->store('items', 'public');
            }

            Item::create($validated);

            return redirect()
                ->route('items.index')
                ->with('success', 'Item ' . $validated['nama'] . ' berhasil dibuat.');
        } catch (\Throwable $th) {

            return redirect()
                ->route('items.index')
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Item $item)
    {
        $item = Item::findOrFail($item->id);
        return view('items.show', compact('item'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item)
    {
        $item = Item::findOrFail($item->id);
        return view('items.edit', compact('item'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Item $item)
    {
        try {
            $validated = $request->validate([
                'kode'   => 'required|unique:items,kode,' . $item->id,
                'nama'   => 'required',
                'harga'  => 'required|numeric',
                'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            ]);

            // handle upload gambar baru
            if ($request->hasFile('gambar')) {
                if ($item->gambar && Storage::disk('public')->exists($item->gambar)) {
                    Storage::disk('public')->delete($item->gambar);
                }
                $validated['gambar'] = $request->file('gambar')->store('items', 'public');
            }

            $item->fill($validated);

            // cek perubahan
            if (! $item->isDirty()) {
                return redirect()
                    ->back()
                    ->withInput()
                    ->with('error', 'Tidak ada perubahan data.');
            }

            $item->save();

            return redirect()
                ->route('items.index')
                ->with('success', 'Item ' . $item->nama . ' berhasil diubah.');
        } catch (\Throwable $th) {
            return redirect()
                ->route('items.index')
                ->with('error', $th->getMessage());
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item)
    {
        $item = Item::findOrFail($item->id);
        $item->delete();
        return redirect()
            ->route('items.index')
            ->with('success', 'Item ' . $item->nama . ' berhasil dihapus.');
    }
}
