<?php

namespace App\Http\Controllers;

use App\Models\Task;
use Illuminate\Http\Request;
use App\ApiResponseTrait;

class TaskController extends Controller
{
    use ApiResponseTrait;

    public function index()
    {
        $tasks = Task::all();
        return $this->successResponse($tasks);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'          => 'required|string|max:255',
            'description'          => 'required|string',
            'status'         => 'nullable|in:pending,in-progress,completed',
            'completionDate' => 'nullable|date',
        ]);

        $task = Task::create($request->only('title', 'status', 'completionDate', 'description'));

        return $this->successResponse($task, 'تسک با موفقیت ساخته شد', 201);
    }

    public function show($id)
    {
        $task = Task::findOrFail($id);
        return $this->successResponse($task);
    }

    public function update(Request $request, $id)
    {
        $task = Task::findOrFail($id);

        $request->validate([
            'title'          => 'sometimes|required|string|max:255',
            'description'          => 'sometimes|required|string',
            'status'         => 'sometimes|required|in:pending,in-progress,completed',
            'completionDate' => 'sometimes|nullable|date',
        ]);

        $task->update($request->only('title', 'status', 'completionDate', 'description'));

        return $this->successResponse($task, 'تسک با موفقیت اپدیت شد');
    }

    public function destroy($id)
    {
        $task = Task::findOrFail($id);
        $task->delete();

        return $this->successResponse(null, 'تسک با موفقیت حذف شد');
    }
}
