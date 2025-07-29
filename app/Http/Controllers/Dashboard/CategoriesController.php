<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CategoriesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $categories = Category::all();
        return view("dashboard.categories.index", compact("categories"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
        $parents = Category::all();
        return view("dashboard.categories.create", compact('parents'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $request->validate([
            'name' => 'required',
            'parent_id' => 'nullable|exists:categories,id',
        ]);
        $request->merge([
            'slug' => Str::slug($request->post('name')),
        ]);
        $category = Category::create($request->all());

        return redirect()->route('categories.index')->with('success', 'Categories Created!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
        try {

            $category = Category::findOrFail($id);
        } catch (\Throwable $th) {

            return redirect()->route('categories.index')
                ->with('info', 'THis Category not found 😒');
        }

        //SELECT * FROM categories WHERE id <> $id
        // AND  (parent_id IS NULL OR parent_id <> $id)

        $parents = Category::where('id', '<>', $id)
            ->where(function ($query) use ($id) {
                $query->whereNull('parent_id')
                    ->Where('parent_id', '<>', $id);
            })
            // ->dd();
            ->get();
        return view('dashboard.categories.edit', compact('category', 'parents'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        try {

            $category = Category::findOrFail($id);
        } catch (\Throwable $th) {

            return redirect()->route('categories.index')
                ->with('info', 'THisn Category not found 😒');
        }
        $category->update($request->all());
        return redirect()->route('categories.index')
            ->with('success', 'Categories Updated!');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {

            $category = Category::findOrFail($id);
        } catch (\Throwable $th) {

            return redirect()->route('categories.index')
                ->with('info', 'THisn Category not found 😒');
        }
        $category->delete();
        return redirect()->route('categories.index')
            ->with('delete', 'Categories Deleted!');
    }
}
