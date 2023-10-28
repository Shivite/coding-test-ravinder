<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePhaseRequest;
use App\Http\Requests\UpdatePhaseRequest;
use App\Models\Phase;
use Illuminate\Support\Facades\Log;

class PhaseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
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
    public function store(StorePhaseRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Phase $phase)
    {
        return $phase->load('tasks.user')->toJson();
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Phase $phase)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePhaseRequest $request, Phase $phase)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Phase $phase)
    {
        //
    }

    public function markTasksCompleted(UpdatePhaseRequest $request, Phase $phase)
    {
        try {
            if ($phase->id) {
                if (!$phase->tasks->isEmpty()) {
                    $phase->tasks->each(function ($task) {
                        if (is_null($task->completed_at)) {
                            $task->update(['completed_at' => now()]);
                        }
                    });
                }
                flash('Tasks marked as completed')->success();

                return response()->json(['message' => 'Tasks marked as completed'], 200);
            }
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            flash('An error occurred while completing tasks')->error();
            return response()->json(['error' => 'An error occurred while completing tasks'], 500);
        }
        
    }
}
