<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Project;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        
        $user = User::query()->findOrFail(Auth::id());
        $projects = $user->projects()
            ->when($request->filled('status'), fn ($q) => $q->where('status', $request->status))
            ->when($request->filled('search'), fn ($q) => $q->where('name', 'like', '%'.$request->search.'%'))
            ->withCount('tasks')
            ->latest()
            ->paginate(9)
            ->withQueryString();

        return view('projects.index', compact('projects'));
    }

    public function store(StoreProjectRequest $request)
    {
        
        $user = User::query()->findOrFail(Auth::id());
        $project = $user->projects()->create($request->validated());

        return redirect()->route('projects.show', $project)->with('status', 'Project created successfully.');
    }

    public function show(Request $request, Project $project)
    {
        $this->authorizeProject($project);

        $filters = $request->validate([
            'status' => ['nullable', 'in:'.implode(',', \App\Models\Task::STATUSES)],
            'search' => ['nullable', 'string', 'max:255'],
            'deadline' => ['nullable', 'in:overdue,today,week,none'],
        ]);

        $status = $filters['status'] ?? null;
        $search = $filters['search'] ?? null;
        $deadline = $filters['deadline'] ?? null;

        $tasksQuery = $project->tasks()
            ->when($status, fn ($q) => $q->where('status', $status))
            ->when($search, fn ($q) => $q->where('title', 'like', '%'.$search.'%'))
            ->when($deadline === 'overdue', fn ($q) => $q->whereNotNull('deadline')->whereDate('deadline', '<', today())->where('status', '!=', 'done'))
            ->when($deadline === 'today', fn ($q) => $q->whereDate('deadline', today()))
            ->when($deadline === 'week', fn ($q) => $q->whereBetween('deadline', [today(), today()->copy()->endOfWeek()]))
            ->when($deadline === 'none', fn ($q) => $q->whereNull('deadline'))
            ->with('attachments')
            ->orderBy('position');

        $tasksByStatus = [
            'todo' => (clone $tasksQuery)->where('status', 'todo')->get(),
            'in_progress' => (clone $tasksQuery)->where('status', 'in_progress')->get(),
            'done' => (clone $tasksQuery)->where('status', 'done')->get(),
        ];

        return view('projects.show', compact('project', 'tasksByStatus', 'status', 'search', 'deadline'));
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        $this->authorizeProject($project);

        $project->update($request->validated());

        return redirect()->route('projects.show', $project)->with('status', 'Project updated successfully.');
    }

    public function destroy(Project $project)
    {
        $this->authorizeProject($project);

        $project->load('tasks.attachments');
        foreach ($project->tasks as $task) {
            foreach ($task->attachments as $attachment) {
                Storage::disk('private')->delete($attachment->path);
            }
        }

        $project->delete();

        return redirect()->route('projects.index')->with('status', 'Project deleted.');
    }


    public function exportCsv(Project $project)
    {
        $this->authorizeProject($project);
        $project->load('tasks');
        $filename = 'project-'.$project->id.'-tasks.csv';
        return response()->streamDownload(function () use ($project) {
            $handle = fopen('php://output', 'w');
            fputcsv($handle, ['Title', 'Description', 'Status', 'Deadline']);

            foreach ($project->tasks as $task) {
                fputcsv($handle, [
                    $task->title,
                    $task->description,
                    $task->status,
                    optional($task->deadline)->format('Y-m-d'),
                ]);
            }
            fclose($handle);
        }, $filename, ['Content-Type' => 'text/csv']);
    }

    private function authorizeProject(Project $project): void
    {
        abort_if($project->user_id !== Auth::id(), 403);
    }
}
