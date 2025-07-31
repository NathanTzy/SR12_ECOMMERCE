<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::latest()->paginate(5);
        return view('pages.backend.Category.index', compact('categories'));
    }


    public function create()
    {
        return view('pages.backend.Category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'img' => 'required|image|max:2048',
        ]);

        $data = $request->only('nama');

        if ($request->hasFile('img')) {
            $data['img'] = $request->file('img')->store('categories', 'public');
        }

        Category::create($data);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil ditambahkan');
    }

    public function edit(Category $category)
    {
        return view('pages.backend.Category.show', compact('category'));
    }

    public function update(Request $request, Category $category)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'img' => 'nullable|image|max:2048',
        ]);

        $data = $request->only('nama');

        if ($request->hasFile('img')) {
            if ($category->img) {
                Storage::disk('public')->delete($category->img);
            }

            $data['img'] = $request->file('img')->store('categories', 'public');
        }

        $category->update($data);

        return redirect()->route('category.index')->with('success', 'Kategori berhasil diperbarui');
    }

    public function destroy(Category $category)
    {
        if ($category->img) {
            Storage::disk('public')->delete($category->img);
        }

        $category->delete();

        return back()->with('success', 'Kategori berhasil dihapus');
    }
}
