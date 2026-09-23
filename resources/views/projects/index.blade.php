@extends('layouts.app')
@section('title', 'Projects')

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
        @if (session('status'))
            <div class="mb-4 rounded-xl border border-teal-300/30 bg-teal-950/60 px-4 py-3 text-sm text-teal-100 shadow-sm backdrop-blur" role="status">
                {{ session('status') }}
            </div>
        @endif

        <div class="glass-pane rounded-2xl p-5 sm:p-6">
            <div class="mb-6 flex flex-col justify-between gap-4 sm:flex-row sm:items-center">
                <h1 class="text-xl font-semibold">Projects</h1>
                <button type="button" x-on:click="$dispatch('open-project-create-modal')" class="btn-squish cursor-pointer inline-flex items-center rounded-lg bg-orange-600 px-4 py-2 text-sm font-medium text-white transition-transform hover:-translate-y-0.5 hover:bg-orange-700 focus:outline-none focus-visible:ring-2 focus-visible:ring-orange-500">
                    + New project
                </button>
            </div>

            <form method="GET" class="flex flex-col gap-3 sm:flex-row">
                <input
                    type="text" name="search" value="{{ request('search') }}" placeholder="Search projects..."
                    class="rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#111827] text-sm w-full sm:w-64 focus:border-teal-500 focus:ring-teal-500"
                >
                <select name="status" class="rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#111827] text-sm focus:border-teal-500 focus:ring-teal-500">
                    <option value="">All statuses</option>
                    @foreach (\App\Models\Project::STATUSES as $s)
                        <option value="{{ $s }}" @selected(request('status') === $s)>{{ ucfirst(str_replace('_', ' ', $s)) }}</option>
                    @endforeach
                </select>
                <button type="submit" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Filter</button>
            </form>
        </div>

        <div class="mt-6 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
            @forelse ($projects as $project)
                <div class="project-card glass-pane relative block rounded-xl p-5 hover:border-teal-500 dark:hover:border-teal-500" data-reveal>

                    <a href="{{ route('projects.show', $project) }}" class="absolute inset-0 rounded-xl" aria-label="Open {{ $project->name }}"></a>

                    <button type="button" x-on:click="$dispatch('open-project-edit-modal-{{ $project->id }}')" class="glass-control absolute right-3 top-3 z-10 cursor-pointer rounded-lg p-1.5 text-slate-600 hover:border-teal-500 hover:text-teal-700 dark:text-slate-400 dark:hover:text-teal-300" aria-label="Edit {{ $project->name }}">
                        <svg aria-hidden="true" class="h-4 w-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 20h9M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5Z" stroke-linecap="round" stroke-linejoin="round"/></svg>
                    </button>

                    <div class="flex items-center justify-between mb-2 pr-8">
                        <h3 class="font-medium">{{ $project->name }}</h3>
                        <span class="text-xs rounded-full px-2 py-0.5 bg-slate-100 dark:bg-slate-800 text-slate-600 dark:text-slate-300">
                            {{ ucfirst(str_replace('_', ' ', $project->status)) }}
                        </span>
                    </div>
                    <p class="text-sm text-slate-500 dark:text-slate-400 line-clamp-2 mb-3">{{ $project->description ?: 'No description.' }}</p>
                    <div class="flex items-center justify-between text-xs text-slate-500 dark:text-slate-400">
                        <span>{{ $project->tasks_count }} tasks</span>
                        <span>{{ $project->deadline ? $project->deadline->format('d M Y') : 'No deadline' }}</span>
                    </div>
                </div>
                @include('projects.partials.edit-modal', ['project' => $project])
            @empty
                <div class="glass-pane col-span-full rounded-xl p-6 text-sm text-slate-600 dark:text-slate-300">No matching projects found.</div>
            @endforelse
        </div>

        @if ($projects->hasPages())
            <div class="glass-pane mt-6 inline-block rounded-xl p-2">{{ $projects->links() }}</div>
        @endif
    </div>
</div>

@include('projects.partials.create-modal')
@endsection
