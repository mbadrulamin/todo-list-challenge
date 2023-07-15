<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function dashboard(Request $request)
    {
        $categories = Category::where('user_id', $request->user()->id)
        ->get()
        ->pluck('category_name', 'id');

        return Inertia::render('Dashboard', [
            'tasks' => $request->user()->tasks()->latest()->get(),
            'categories' => $categories,
        ]);
    }
}
