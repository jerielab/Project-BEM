
<div
    x-data="{ open: false }"
    x-on:open-project-edit-modal-{{ $project->id }}.window="open = true"
    x-show="open" x-cloak
    x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-on:click.outside="open = false" x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
    role="dialog" aria-modal="true" aria-labelledby="edit-project-title-{{ $project->id }}"
>
    <div 
        class="glass-modal w-full max-w-lg rounded-xl p-6" x-on:click.stop
        x-transition:enter="transition duration-300 ease-spring"
        x-transition:enter-start="opacity-0 scale-90 -rotate-1"
        x-transition:enter-end="opacity-100 scale-100 rotate-0"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100 scale-100"
        x-transition:leave-end="opacity-0 scale-95"
    >    
        <div class="mb-4 flex items-center justify-between">
            <h2 id="edit-project-title-{{ $project->id }}" class="font-semibold">Edit project</h2>
            <button type="button" x-on:click="open = false" class="cursor-pointer rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Close">
                <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('projects.update', $project) }}" class="space-y-4">
            @csrf
            @method('PUT')
            @include('projects.partials.form', ['project' => $project, 'inModal' => true])
        </form>

        <form method="POST" action="{{ route('projects.destroy', $project) }}" class="mt-5 border-t border-rose-500/20 pt-4" onsubmit="return confirm('Delete this project and all of its tasks and attachments? This cannot be undone.');">
            @csrf
            @method('DELETE')
            <div class="flex items-center justify-between gap-4">
                <p class="text-xs leading-5 text-slate-500 dark:text-slate-400">Deleting a project also permanently removes its tasks and attachments.</p>
                <button type="submit" class="shrink-0 cursor-pointer rounded-lg border border-rose-500/50 bg-rose-500/10 px-3 py-2 text-sm font-medium text-rose-700 transition hover:bg-rose-600 hover:text-white focus:outline-none focus-visible:ring-4 focus-visible:ring-rose-200 dark:text-rose-300 dark:hover:bg-rose-500">Delete project</button>
            </div>
        </form>
    </div>
</div>
