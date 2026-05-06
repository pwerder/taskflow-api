<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->get();
        return response()->json($tasks, 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());
        return response()->json(['data' => $task], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Request $request)
    {
        abort_if($task->user_id !== $request->user()->id, 404);
        return response()->json($task, 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        abort_if($task->user_id !== $request->user()->id, 404);

        $task->update($request->validated());
        return response()->json($task, 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
    {
        abort_if($task->user_id !== $request->user()->id, 404);

        $task->delete();

        return response()->noContent();
    }
}
