<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->authorize('create', Task::class);

        $validated = $request->validate([
            'title' => 'required|string',
            'due_date' => 'nullable|date',
            'category_id' => 'nullable',
        ]);

        $task = new Task();
        $task->title = $validated['title'];
        $task->due_date = $validated['due_date'];
        $task->category_id = $validated['category_id'];
        $task->user()->associate($request->user());
        $task->save();

        return back(303);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Task $task)
    {

        // $currentTask = Task::find($task);
      // dd($task);
        $this->authorize('update', $task);

        $validated = $request->validate([
            'is_completed' => 'required|boolean',
        ]);

        $task->is_completed = $validated['is_completed'];

        $task->save();

        return back(303);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        // $currentTask = Task::find($task);

        $this->authorize('delete', $task);
        $task->delete();

        return back(303);
    }
}
