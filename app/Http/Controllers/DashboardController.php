<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $category = Category::where('user_id', $request->user()->id)
        ->get()
        ->pluck('category_name', 'id');

        $categories = Category::where('user_id', $request->user()->id)
        ->get();

        return Inertia::render('Dashboard', [
            'tasks' => $request->user()->tasks()->latest()->get(),
            'category' => $category,
            'categories' => $categories,

        ]);
    }
}
