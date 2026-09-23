<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Attachment;
use App\Models\Project;
use App\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function store(StoreTaskRequest $request, Project $project)
    {
        $this->authorizeProject($project);

        $data = $request->validated();
        unset($data['attachments']);
        $data['user_id'] = Auth::id();
        $data['position'] = $project->tasks()->where('status', $request->status)->max('position') + 1;

        $task = $project->tasks()->create($data);

        $this->storeAttachments($request, $task);

        return redirect()->route('projects.show', $project)->with('status', 'Task added successfully.');
    }

    public function update(UpdateTaskRequest $request, Project $project, Task $task)
    {
        $this->authorizeTask($project, $task);

        $data = $request->validated();
        unset($data['attachments']);
        $task->update($data);

        $this->storeAttachments($request, $task);

        return redirect()->route('projects.show', $project)->with('status', 'Task updated successfully.');
    }

    public function destroy(Project $project, Task $task)
    {
        $this->authorizeTask($project, $task);

        foreach ($task->attachments as $attachment) {
            Storage::disk('private')->delete($attachment->path);
        }

        $task->delete();

        return redirect()->route('projects.show', $project)->with('status', 'Task deleted.');
    }

    
    public function reorder(Request $request, Project $project, Task $task)
    {
        $this->authorizeTask($project, $task);

        $validated = $request->validate([
            'status' => ['required', 'in:'.implode(',', Task::STATUSES)],
            'position' => ['required', 'integer', 'min:0'],
        ]);

        DB::transaction(function () use ($project, $task, $validated) {
            $siblings = Task::where('project_id', $project->id)
                ->where('status', $validated['status'])
                ->whereKeyNot($task->id)
                ->orderBy('position')
                ->get();

            $insertAt = min($validated['position'], $siblings->count());
            $siblings->splice($insertAt, 0, [$task]);

            foreach ($siblings->values() as $position => $sibling) {
                $sibling->update([
                    'status' => $validated['status'],
                    'position' => $position,
                ]);
            }
        });

        return response()->json(['ok' => true, 'message' => 'Task position updated.']);
    }

    public function deleteAttachment(Project $project, Task $task, Attachment $attachment)
    {
        $this->authorizeTask($project, $task);
        abort_if($attachment->task_id !== $task->id, 404);

        Storage::disk('private')->delete($attachment->path);
        $attachment->delete();

        return back()->with('status', 'Attachment deleted.');
    }

    private function storeAttachments(Request $request, Task $task): void
    {
        if (! $request->hasFile('attachments')) {
            return;
        }

        foreach ($request->file('attachments') as $file) {
            $path = $file->store('tasks/'.$task->id, 'private');

            $task->attachments()->create([
                'original_name' => $file->getClientOriginalName(),
                'path' => $path,
                'mime_type' => $file->getClientMimeType(),
                'size_bytes' => $file->getSize(),
            ]);
        }
    }

    public function downloadAttachment(Project $project, Task $task, Attachment $attachment)
    {
        $this->authorizeTask($project, $task);
        abort_if($attachment->task_id !== $task->id, 404);

        return response()->download(
            Storage::disk('private')->path($attachment->path),
            $attachment->original_name,
            ['Content-Type' => $attachment->mime_type ?? 'application/octet-stream'],
        );
    }

    private function authorizeProject(Project $project): void
    {
        abort_if($project->user_id !== Auth::id(), 403);
    }

    private function authorizeTask(Project $project, Task $task): void
    {
        $this->authorizeProject($project);
        abort_if($task->project_id !== $project->id, 404);
    }
}
