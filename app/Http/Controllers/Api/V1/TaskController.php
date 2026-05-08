<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Http\Resources\TaskResource;
use App\Jobs\ProcessTaskCreated;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Cache;

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $query = $request->user()->tasks();

        $status = $request->get('status');

        if ($status) {
            $query->where('status', $status);
        }

        $sort = $request->get('sort', 'created_at');
        $direction = $request->get('direction', 'desc');

        $allowedSorts = ['created_at', 'title', 'status'];

        if (in_array($sort, $allowedSorts)) {
            $query->orderBy($sort, $direction);
        }

        $tasks = $query->paginate(3);

        return TaskResource::collection($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        $task = $request->user()->tasks()->create($request->validated());

        ProcessTaskCreated::dispatch($task);

        $task->refresh();

        Cache::forget('task_user_' . $request->user()->id);

        return response()->json([
            'data' => new TaskResource($task)
        ], Response::HTTP_CREATED);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task, Request $request)
    {
        $this->ensureTaskOwnership($task, $request);

        $cacheKey = 'task_' . $task->id;

        $task = Cache::remember(
            $cacheKey,
            60,
            fn() => Task::find($task->id)?->toArray()
        );

        return TaskResource::make((object) $task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $this->ensureTaskOwnership($task, $request);

        $task->update($request->validated());

        Cache::forget('task_user_' . $request->user()->id);

        return new TaskResource($task);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task, Request $request)
    {
        $this->ensureTaskOwnership($task, $request);

        $task->delete();

        Cache::forget('task_user_' . $request->user()->id);

        return response()->noContent();
    }

    private function ensureTaskOwnership(Task $task, Request $request): void
    {
        abort_if($task->user_id !== $request->user()->id, 404);
    }
}
