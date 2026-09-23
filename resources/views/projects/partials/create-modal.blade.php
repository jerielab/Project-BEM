
<div
    x-data="{ open: {{ request()->boolean('create') ? 'true' : 'false' }} }"
    x-on:open-project-create-modal.window="open = true"
    x-show="open" x-cloak
    x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-on:click.outside="open = false" x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
    role="dialog" aria-modal="true" aria-labelledby="create-project-title"
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
            <h2 id="create-project-title" class="font-semibold">New project</h2>
            <button type="button" x-on:click="open = false" class="cursor-pointer rounded-lg p-1 text-slate-400 hover:bg-slate-100 hover:text-slate-600 dark:hover:bg-slate-800 dark:hover:text-slate-300" aria-label="Close">
                <svg aria-hidden="true" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M6 6l12 12M18 6L6 18" stroke-linecap="round" stroke-linejoin="round"/></svg>
            </button>
        </div>

        <form method="POST" action="{{ route('projects.store') }}" class="space-y-4">
            @csrf
            @include('projects.partials.form', ['inModal' => true])
        </form>
    </div>
</div>
