@extends('layouts.app')
@section('title', $project->name)

@section('content')
<style>
@media (prefers-reduced-motion: reduce) {
    .dashboard-canvas__video { display: none; }
}
</style>

<div class="dashboard-canvas relative isolate min-h-screen overflow-hidden px-4 py-4 sm:px-6 lg:px-8" style="background-image: url('https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260827_202133_0ba6de7c-285d-43dc-b7ab-8c54c73707cb.png'); background-size: cover; background-position: center;">

    <video data-video-guard class="dashboard-canvas__video pointer-events-none absolute inset-0 z-0 h-full w-full object-cover" autoplay loop muted playsinline preload="metadata" poster="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260827_202133_0ba6de7c-285d-43dc-b7ab-8c54c73707cb.png" aria-hidden="true">
        <source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260827_202422_51eae59a-2459-4c84-907c-cc5edfe5fea7.mp4" type="video/mp4">
    </video>

    <div class="relative z-10">
        <a href="{{ route('projects.index') }}" class="mb-3 inline-flex items-center gap-1 text-sm font-medium text-white/90 drop-shadow hover:text-white">
            <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M15 19l-7-7 7-7" stroke-linecap="round" stroke-linejoin="round"/></svg>
            Back to projects
        </a>

        @if (session('status'))
            <div class="mb-3 rounded-xl border border-teal-300/30 bg-teal-950/60 px-4 py-3 text-sm text-teal-100 shadow-sm backdrop-blur" role="status">
                {{ session('status') }}
            </div>
        @endif

        <div class="glass-pane mt-3 rounded-2xl p-5 sm:p-6">
            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-2">
                <div>
                    <h1 class="text-xl font-semibold">{{ $project->name }}</h1>
                    <p class="text-sm text-slate-500 dark:text-slate-400 mt-1">{{ $project->description }}</p>
                </div>
                <div class="flex gap-2">
                    <a href="{{ route('projects.export', $project) }}" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Export CSV</a>
                    <button type="button" x-data x-on:click="$dispatch('open-project-edit-modal-{{ $project->id }}')" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-3 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Edit project</button>
                </div>
            </div>

            <div class="text-xs text-slate-500 dark:text-slate-400 mb-6">
                Deadline: {{ $project->deadline?->format('d M Y') ?? '—' }} · Status: {{ ucfirst(str_replace('_', ' ', $project->status)) }}
            </div>

            <form method="GET" class="flex flex-col sm:flex-row gap-3" aria-label="Filter tasks">
                <input type="text" name="search" value="{{ $search }}" placeholder="Search tasks..."
                    class="rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#111827] text-sm w-full sm:w-64 focus:border-teal-500 focus:ring-teal-500">
                <select name="status" class="rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#111827] text-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">All statuses</option>
                    @foreach (\App\Models\Task::STATUSES as $s)
                        <option value="{{ $s }}" @selected($status === $s)>{{ str_replace('_', ' ', $s) }}</option>
                    @endforeach
                </select>
                <select name="deadline" class="rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#111827] text-sm focus:border-teal-500 focus:ring-teal-500" aria-label="Filter deadline">
                    <option value="">All deadlines</option>
                    <option value="overdue" @selected($deadline === 'overdue')>Overdue</option>
                    <option value="today" @selected($deadline === 'today')>Today</option>
                    <option value="week" @selected($deadline === 'week')>This week</option>
                    <option value="none" @selected($deadline === 'none')>No deadline</option>
                </select>
                <button type="submit" class="cursor-pointer min-h-11 rounded-lg border border-slate-300 dark:border-[#334155] px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Apply</button>
                @if ($status || $search || $deadline)
                    <a href="{{ route('projects.show', $project) }}" class="inline-flex min-h-11 items-center justify-center rounded-lg px-3 py-2 text-sm text-slate-600 hover:text-teal-700 dark:text-slate-300 dark:hover:text-teal-300">Reset</a>
                @endif
                <button type="button" x-data x-on:click="$dispatch('open-task-modal')" class="cursor-pointer ml-auto rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white hover:bg-orange-700">
                    + New task
                </button>
            </form>
        </div>

        <div class="mt-6 grid grid-cols-1 md:grid-cols-3 gap-4">
            @foreach ([
                'todo' => 'To-do',
                'in_progress' => 'In Progress',
                'done' => 'Done',
            ] as $key => $label)
            <div class="glass-subpane rounded-xl p-3" data-reveal>
                <h3 class="text-sm font-semibold mb-3 px-1">{{ $label }} <span class="text-slate-400 font-normal">({{ $tasksByStatus[$key]->count() }})</span></h3>
                <div data-kanban-column="{{ $key }}" class="space-y-3 min-h-[80px]" aria-label="{{ $label }} column">
                    @foreach ($tasksByStatus[$key] as $task)
                        @include('tasks.partials.card', ['task' => $task])
                    @endforeach
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

@include('tasks.partials.form', ['project' => $project])
@include('projects.partials.edit-modal', ['project' => $project])

<script>
document.addEventListener('DOMContentLoaded', () => window.initKanban(@json($project->id)));
</script>
@endsection
