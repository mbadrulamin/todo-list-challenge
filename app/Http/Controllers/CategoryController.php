<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCategoryRequest;
use App\Http\Requests\UpdateCategoryRequest;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $categories = Category::where('user_id', $request->user()->id)->get();

        return Inertia::render('Category', [
          'categories' => $categories,
      ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia('CategoryForm');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreCategoryRequest $request)
    {
      $this->authorize('create', Category::class);

      $validated = $request->validate([
          'category_name' => 'required|string',
      ]);

      $category = new Category();
      $category->category_name = $validated['category_name'];
      $category->user()->associate($request->user());
      $category->save();

      return to_route('categories.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(Category $category)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Category $category)
    {
        return Inertia('CategoryForm', [
          'categories' => $category
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateCategoryRequest $request, Category $category)
    {
        $validatedData = $request->validate([
            'category_name' => 'required|string',
        ]);

        $category->category_name = $validatedData['category_name'];
        $category->save();

        // Optionally, you can return a response or redirect
        return to_route('categories.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Category $category)
    {
      $this->authorize('delete', $category);
      $category->delete();

      return back(303);
    }
}
