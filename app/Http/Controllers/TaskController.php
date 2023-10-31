<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use Illuminate\Http\Request;

class TaskController extends Controller
{

    public function kanban()
    {
        return view('tasks.index');
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return \App\Models\Phase::with('tasks.user')->withCount('tasks')->get();
    }

    /**
     * Display a listing of the Users resource.
     */
    public function users()
    {
        return \App\Models\User::all();
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request)
    {
        // Create a new task from the $request
        $validatedData = $request->validated();
        $phase = \App\Models\Phase::find($request->phase_id);
        if ($phase) {
            if ($phase->completed) 
                $validatedData['completed_at'] =  now();
            $task = Task::create($validatedData);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Task $task)
    {
        $task = Task::find($task->id);
        return response()->json($task);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, Task $task)
    {
        $validatedData = $request->validated();
        $phase = \App\Models\Phase::find($request->phase_id);
        if ($phase) {
            if ($phase->completed) {
                if (is_null($task->completed_at)) {
                    $validatedData['completed_at'] =  now();
                    $task->update($validatedData);
                }
            }else
                $task->update($validatedData);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Task $task)
    {
        Task::destroy($task->id);
    }
}
