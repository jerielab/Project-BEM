@extends('layouts.app')
@section('title', 'Dashboard')

@section('content')
@php
    $completion = $stats['tasks_total'] > 0 ? (int) round(($stats['tasks_done'] / $stats['tasks_total']) * 100) : 0;
    $todo = $taskStates['todo'] ?? 0;
    $inProgress = $taskStates['in_progress'] ?? 0;
    $done = $taskStates['done'] ?? 0;

    $metrics = [
        ['label' => 'Active projects', 'value' => $stats['projects_total'], 'note' => 'Workspaces in motion', 'tone' => 'teal', 'href' => route('projects.index')],
        ['label' => 'In progress', 'value' => $inProgress, 'note' => 'Tasks being worked on', 'tone' => 'violet', 'href' => null],
        ['label' => 'Completed', 'value' => $stats['tasks_done'], 'note' => $completion.'% of all tasks', 'tone' => 'sky', 'href' => null],
        ['label' => 'Needs attention', 'value' => $stats['tasks_overdue'], 'note' => $stats['tasks_overdue'] ? 'Overdue — click to view' : 'Nothing overdue', 'tone' => $stats['tasks_overdue'] ? 'coral' : 'teal', 'href' => $stats['tasks_overdue'] ? '#upcoming' : null],
    ];
@endphp


<style>
    @media (prefers-reduced-motion: reduce) {
        .dashboard-canvas__video { display: none; }
    }
</style>

<div class="dashboard-canvas relative isolate min-h-screen overflow-hidden px-4 py-4 sm:px-6 sm:py-6 lg:px-8 lg:py-8" style="background-image: url('https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260827_202133_508c64b8-a31e-4290-bdfc-1187df70e0a6.png'); background-size: cover; background-position: center;">

    <video data-video-guard class="dashboard-canvas__video pointer-events-none absolute inset-0 z-0 h-full w-full object-cover" autoplay loop muted playsinline preload="metadata" poster="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260827_202133_508c64b8-a31e-4290-bdfc-1187df70e0a6.png" aria-hidden="true">
        <source src="https://d8j0ntlcm91z4.cloudfront.net/user_38xzZboKViGWJOttwIXH07lWA1P/hf_20260827_202422_3ffb4889-c520-432d-8458-038009eb40df.mp4" type="video/mp4">
    </video>
    <div class="relative z-10">
        <div class="mb-7 flex flex-col gap-5 lg:flex-row lg:items-end lg:justify-between" data-reveal>
            <div>
                <p class="mb-2 flex items-center gap-2 text-xs font-bold uppercase tracking-[0.18em] text-teal-700 dark:text-teal-300"><span class="h-2 w-2 rounded-full bg-teal-500"></span> Control room</p>
                <h1 class="font-instrument text-4xl tracking-[-0.04em] text-white drop-shadow-[0_3px_16px_rgba(0,0,0,.45)] sm:text-5xl">Your work, <span class="italic text-emerald-300">in flow.</span></h1>
                <p class="mt-2 max-w-2xl font-inter text-base leading-7 text-white/85 drop-shadow sm:text-lg">See your capacity, keep your rhythm, and move important work across the finish line.</p>
            </div>
            <a href="{{ route('projects.index', ['create' => 1]) }}" class="inline-flex min-h-11 items-center justify-center gap-2 rounded-xl bg-slate-950 px-5 py-3 text-sm font-semibold text-white shadow-lg shadow-slate-950/15 transition-transform hover:-translate-y-0.5 hover:bg-teal-700 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-300 dark:bg-teal-400 dark:text-slate-950 dark:hover:bg-teal-300">
                <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 5v14M5 12h14" stroke-linecap="round"/></svg> New project
            </a>
        </div>

        <section class="dashboard-hero mb-6 overflow-hidden rounded-[2rem] p-5 text-white shadow-2xl shadow-slate-950/15 sm:p-8" aria-labelledby="focus-title" data-reveal>
            <div class="dashboard-hero__glow" aria-hidden="true"></div>
            <div class="relative grid gap-8 lg:grid-cols-[1.25fr_0.75fr] lg:items-center">
                <div>
                    <p class="text-xs font-bold uppercase tracking-[0.18em] text-teal-200">This week’s focus</p>
                    <h2 id="focus-title" class="mt-3 max-w-xl text-3xl font-semibold tracking-[-0.04em] sm:text-4xl">{{ $stats['tasks_overdue'] > 0 ? 'Some work needs rescuing.' : 'Your rhythm looks healthy.' }}</h2>
                    <p class="mt-3 max-w-lg text-sm leading-6 text-slate-300">{{ $stats['tasks_overdue'] > 0 ? $stats['tasks_overdue'].' tasks are past their deadline. Start with the nearest item to regain momentum.' : 'No tasks are overdue. Keep your flow by completing the next task.' }}</p>
                    <div class="mt-6 flex flex-wrap gap-3">
                        <a href="#upcoming" class="inline-flex min-h-11 items-center rounded-xl bg-white px-4 py-2 text-sm font-semibold text-slate-950 transition-transform hover:-translate-y-0.5 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-200">Open queue</a>
                        <a href="{{ route('projects.index') }}" class="inline-flex min-h-11 items-center rounded-xl border border-white/20 px-4 py-2 text-sm font-semibold text-white hover:bg-white/10 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-200">View projects</a>
                    </div>
                </div>
                <div class="flow-orbit mx-auto w-full max-w-[300px]" aria-label="{{ $completion }} percent of tasks completed">
                    <div class="flow-orbit__ring flow-orbit__ring--outer"></div><div class="flow-orbit__ring flow-orbit__ring--inner"></div>
                    <div class="flow-orbit__card">
                        <span class="text-xs font-semibold uppercase tracking-[0.16em] text-slate-400">Complete</span>
                        <strong class="mt-1 block text-5xl font-semibold tracking-[-0.06em] tabular-nums">{{ $completion }}<small class="text-2xl">%</small></strong>
                        <span class="mt-2 block text-xs text-teal-200">{{ $done }} of {{ $stats['tasks_total'] }} tasks</span>
                    </div>
                    <span class="flow-orbit__satellite flow-orbit__satellite--one">{{ $inProgress }} active</span>
                    <span class="flow-orbit__satellite flow-orbit__satellite--two">{{ $todo }} waiting</span>
                </div>
            </div>
        </section>

        <section class="mb-6 grid gap-3 sm:grid-cols-2 xl:grid-cols-4" aria-label="Work summary">
            @foreach ($metrics as $metric)
                @if ($metric['href'])
                    <a href="{{ $metric['href'] }}" class="metric-card metric-card--{{ $metric['tone'] }} block rounded-2xl border border-slate-200/80 bg-white p-5 transition-transform hover:-translate-y-0.5 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-200 dark:border-slate-700 dark:bg-slate-900" data-reveal>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $metric['label'] }}</p>
                        <div class="mt-4 flex items-end justify-between gap-3"><strong class="text-4xl font-semibold tracking-[-0.05em] tabular-nums text-slate-950 dark:text-white">{{ $metric['value'] }}</strong><span class="h-3 w-3 rounded-full" aria-hidden="true"></span></div>
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">{{ $metric['note'] }}</p>
                    </a>
                @else
                    <article class="metric-card metric-card--{{ $metric['tone'] }} rounded-2xl border border-slate-200/80 bg-white p-5 dark:border-slate-700 dark:bg-slate-900" data-reveal>
                        <p class="text-sm font-medium text-slate-600 dark:text-slate-300">{{ $metric['label'] }}</p>
                        <div class="mt-4 flex items-end justify-between gap-3"><strong class="text-4xl font-semibold tracking-[-0.05em] tabular-nums text-slate-950 dark:text-white">{{ $metric['value'] }}</strong><span class="h-3 w-3 rounded-full" aria-hidden="true"></span></div>
                        <p class="mt-3 text-xs text-slate-500 dark:text-slate-400">{{ $metric['note'] }}</p>
                    </article>
                @endif
            @endforeach
        </section>

        <div class="grid gap-6 xl:grid-cols-[1.1fr_0.9fr]">
            <section class="dashboard-panel rounded-[1.5rem] border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-6" aria-labelledby="projects-title" data-reveal>
                <div class="mb-6 flex items-center justify-between gap-4"><div><p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Runway</p><h2 id="projects-title" class="mt-1 text-xl font-semibold tracking-tight">Recent projects</h2></div><a href="{{ route('projects.index') }}" class="text-sm font-semibold text-teal-700 hover:text-teal-900 hover:underline focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-200 dark:text-teal-300 dark:hover:text-teal-100">All projects</a></div>
                <div class="space-y-3">
                    @forelse ($projects as $project)
                        @php $progress = $project->tasks_count ? (int) round(($project->completed_tasks_count / $project->tasks_count) * 100) : 0; @endphp
                        <a href="{{ route('projects.show', $project) }}" class="project-row group block rounded-xl border border-transparent p-3 transition-colors hover:border-teal-100 hover:bg-teal-50/60 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-200 dark:hover:border-teal-900 dark:hover:bg-teal-950/30" data-reveal>
                            <div class="flex items-start justify-between gap-4">
                                <div class="min-w-0">
                                    <div class="flex items-center gap-2">
                                        <h3 class="truncate font-semibold text-slate-900 dark:text-slate-100">{{ $project->name }}</h3>
                                        @if ($project->overdue_tasks_count > 0)
                                            <span class="inline-flex shrink-0 items-center rounded-full bg-rose-100 px-2 py-0.5 text-[11px] font-semibold text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">{{ $project->overdue_tasks_count }} overdue</span>
                                        @endif
                                    </div>
                                    <p class="mt-1 text-xs text-slate-500 dark:text-slate-400">{{ $project->tasks_count }} tasks · {{ $project->deadline?->format('d M Y') ?? 'No deadline' }}</p>
                                </div>
                                <span class="shrink-0 text-sm font-semibold tabular-nums text-slate-700 dark:text-slate-200">{{ $progress }}%</span>
                            </div>
                            <div class="mt-3 h-1.5 overflow-hidden rounded-full bg-slate-100 dark:bg-slate-800"><div class="h-full rounded-full bg-gradient-to-r from-teal-500 to-cyan-400" style="width: {{ $progress }}%"></div></div>
                        </a>
                    @empty
                        <div class="rounded-xl border border-dashed border-slate-300 p-6 text-sm leading-6 text-slate-600 dark:border-slate-600 dark:text-slate-300">No projects yet. <a href="{{ route('projects.create') }}" class="font-semibold text-teal-700 underline dark:text-teal-300">Create your first project</a> to start shaping your workflow.</div>
                    @endforelse
                </div>
            </section>

            <section id="upcoming" class="dashboard-panel rounded-[1.5rem] border border-slate-200/80 bg-white p-5 shadow-sm dark:border-slate-700 dark:bg-slate-900 sm:p-6" aria-labelledby="queue-title" data-reveal>
                @if ($overdueTasks->isNotEmpty())
                    <div class="mb-4">
                        <p class="text-xs font-bold uppercase tracking-[0.16em] text-rose-600 dark:text-rose-400">Needs rescue</p>
                        <h2 class="mt-1 text-xl font-semibold tracking-tight">Overdue tasks</h2>
                    </div>
                    <ol class="mb-6 space-y-1">
                        @foreach ($overdueTasks as $task)
                            <li data-reveal>
                                <a href="{{ route('projects.show', ['project' => $task->project, 'deadline' => 'overdue']) }}" class="flex items-center gap-3 rounded-xl border border-rose-100 bg-rose-50/60 p-3 transition-colors hover:bg-rose-100/70 focus:outline-none focus-visible:ring-4 focus-visible:ring-rose-200 dark:border-rose-900 dark:bg-rose-950/30 dark:hover:bg-rose-950/50">
                                    <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-rose-100 text-rose-700 dark:bg-rose-950/60 dark:text-rose-300">
                                        <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M12 9v4m0 4h.01M10.29 3.86 1.82 18a2 2 0 0 0 1.71 3h16.94a2 2 0 0 0 1.71-3L13.71 3.86a2 2 0 0 0-3.42 0Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                                    </span>
                                    <div class="min-w-0 flex-1">
                                        <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $task->title }}</p>
                                        <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">{{ $task->project->name }}</p>
                                    </div>
                                    <time datetime="{{ $task->deadline->toDateString() }}" class="shrink-0 text-right text-xs font-semibold text-rose-700 dark:text-rose-300">Overdue</time>
                                </a>
                            </li>
                        @endforeach
                    </ol>
                @endif

                <div class="mb-6"><p class="text-xs font-bold uppercase tracking-[0.16em] text-slate-500 dark:text-slate-400">Queue</p><h2 id="queue-title" class="mt-1 text-xl font-semibold tracking-tight">Upcoming deadlines</h2></div>
                <ol class="space-y-1">
                    @forelse ($upcomingTasks as $task)
                        <li data-reveal>
                            <a href="{{ route('projects.show', $task->project) }}" class="queue-item flex items-center gap-3 rounded-xl p-3 transition-colors hover:bg-slate-50 focus:outline-none focus-visible:ring-4 focus-visible:ring-teal-200 dark:hover:bg-slate-800/60">
                                <span class="flex h-10 w-10 shrink-0 items-center justify-center rounded-xl bg-slate-100 text-slate-600 dark:bg-slate-800 dark:text-slate-300">
                                    <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><circle cx="12" cy="12" r="8"/><path d="M12 7v5l3 2" stroke-linecap="round"/></svg>
                                </span>
                                <div class="min-w-0 flex-1">
                                    <p class="truncate text-sm font-semibold text-slate-900 dark:text-slate-100">{{ $task->title }}</p>
                                    <p class="mt-0.5 truncate text-xs text-slate-500 dark:text-slate-400">{{ $task->project->name }}</p>
                                </div>
                                <time datetime="{{ $task->deadline->toDateString() }}" class="shrink-0 text-right text-xs font-semibold text-slate-600 dark:text-slate-300">{{ $task->deadline->format('d M') }}</time>
                            </a>
                        </li>
                    @empty
                        <li class="rounded-xl border border-dashed border-slate-300 p-6 text-sm text-slate-600 dark:border-slate-600 dark:text-slate-300">No upcoming deadlines. Time to prepare the next step.</li>
                    @endforelse
                </ol>
            </section>
        </div>
    </div>
</div>
@endsection
