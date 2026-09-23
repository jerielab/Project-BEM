<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function __invoke()
    {
        
        $user = User::query()->findOrFail(Auth::id());
        $userId = $user->id;

        $projects = $user->projects()
            ->withCount('tasks')
            ->withCount([
                'tasks as completed_tasks_count' => fn ($query) => $query->where('status', 'done'),
                'tasks as overdue_tasks_count' => fn ($query) => $query
                    ->where('status', '!=', 'done')
                    ->whereNotNull('deadline')
                    ->whereDate('deadline', '<', today()),
            ])
            ->latest()
            ->take(5)
            ->get();

        $stats = [
            'projects_total' => $user->projects()->count(),
            'tasks_total' => Task::where('user_id', $userId)->count(),
            'tasks_done' => Task::where('user_id', $userId)->where('status', 'done')->count(),
            'tasks_overdue' => Task::where('user_id', $userId)
                ->where('status', '!=', 'done')
                ->whereNotNull('deadline')
                ->whereDate('deadline', '<', today())
                ->count(),
        ];

        
        
        $overdueTasks = Task::where('user_id', $userId)
            ->where('status', '!=', 'done')
            ->whereNotNull('deadline')
            ->whereDate('deadline', '<', today())
            ->orderBy('deadline')
            ->with('project')
            ->take(5)
            ->get();

        
        
        $upcomingTasks = Task::where('user_id', $userId)
            ->where('status', '!=', 'done')
            ->whereNotNull('deadline')
            ->whereDate('deadline', '>=', today())
            ->orderBy('deadline')
            ->with('project')
            ->take(8)
            ->get();

        $taskStates = Task::where('user_id', $userId)
            ->selectRaw('status, count(*) as total')
            ->groupBy('status')
            ->pluck('total', 'status');

        return view('dashboard.index', compact(
            'projects',
            'stats',
            'upcomingTasks',
            'overdueTasks',
            'taskStates'
        ));
    }
}