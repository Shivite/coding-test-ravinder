<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Task;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class StatisticsController extends Controller
{
    public function index()
    {
        return view('statistics.index');
    }

    // public function users()
    // {
    //     return \App\Models\User::all();
    // }

    public function userStatistics(Request $request)
    {
        $result = [];
        $currentMonthStart = Carbon::now()->startOfMonth();
        $currentMonthEnd = Carbon::now()->endOfMonth();
        $currentWeekStart = Carbon::now()->startOfWeek();
        $currentWeekEnd = Carbon::now()->endOfWeek();

        $usersWithTaskCounts = User::select('users.*')
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.user_id = users.id) as total_task_count'))
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.user_id = users.id AND tasks.completed_at BETWEEN ? AND ?) as completed_task_count_in_month'))
            ->addSelect(DB::raw('(SELECT COUNT(*) FROM tasks WHERE tasks.user_id = users.id AND tasks.completed_at BETWEEN ? AND ?) as completed_task_count_in_week'))
            ->setBindings([$currentMonthStart, $currentMonthEnd, $currentWeekStart, $currentWeekEnd])
            ->get();
        $result['usersWithTaskCounts'] = $usersWithTaskCounts;
        $result['completedThisWeek'] = Task::whereNotNull('completed_at')
        ->whereBetween('completed_at', [
            Carbon::now()->startOfWeek(),
            Carbon::now()->endOfWeek()
        ])->count();
        $result['completedThisMonth'] = Task::whereNotNull('completed_at')
            ->whereYear('completed_at', Carbon::now()->year)
            ->whereMonth('completed_at', Carbon::now()->month)
            ->count();
        // $combinedArray = array_merge($usersWithTaskCounts, $completedThisWeek, $completedThisMonth);
        return response()->json($result);
        }
}
