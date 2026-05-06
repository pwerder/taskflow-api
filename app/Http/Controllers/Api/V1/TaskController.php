<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $tasks = $request->user()->tasks()->get();
        return response()->json([
            'data' => TaskResource::collection($tasks)
        ], Response::HTTP_OK);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());

        $task->refresh();

        return response()->json([
            'data' => new TaskResource($task)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Request $request)
    {
        abort_if($task->user_id !== $request->user()->id, 404);
        return response()->json([
            'data' => new TaskResource($task)
        ], Response::HTTP_OK);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        abort_if($task->user_id !== $request->user()->id, 404);

        $task->update($request->validated());
        return response()->json([
            'data' => new TaskResource($task)
        ], Response::HTTP_OK);
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
