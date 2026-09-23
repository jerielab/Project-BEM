<div
    data-task-id="{{ $task->id }}"
    x-data="{ open: false }"
    x-on:close-task-modal-{{ $task->id }}.window="open = false"
    class="task-card glass-pane cursor-move rounded-lg p-3"
    role="button" tabindex="0"
    x-on:click="open = true"
    x-on:keydown.enter="open = true"
>
    <div class="flex items-start justify-between gap-2">
        <p class="text-sm font-medium">{{ $task->title }}</p>
        @if ($task->isOverdue())
            <span class="shrink-0 text-[10px] rounded-full bg-red-100 dark:bg-red-950 text-red-700 dark:text-red-300 px-2 py-0.5">Overdue</span>
        @endif
    </div>
    @if ($task->description)
        <p class="mt-1 text-xs text-slate-500 dark:text-slate-400 line-clamp-2">{{ $task->description }}</p>
    @endif
    <div class="mt-2 flex items-center justify-between text-[11px] text-slate-400">
        <span>{{ $task->deadline?->format('d M') ?? 'No deadline' }}</span>
        @if ($task->attachments->count())
            <span class="inline-flex items-center gap-1"><svg aria-hidden="true" class="h-3.5 w-3.5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.8"><path d="m21.4 11.6-8.8 8.8a6 6 0 0 1-8.5-8.5l9.1-9.1a4 4 0 1 1 5.7 5.7l-9.1 9.1a2 2 0 0 1-2.8-2.8l8.4-8.4" stroke-linecap="round" stroke-linejoin="round"/></svg>{{ $task->attachments->count() }}</span>
        @endif
    </div>

    
    <template x-teleport="body">
    <div
        x-show="open" x-cloak
        x-transition:enter="transition duration-200 ease-out"
        x-transition:enter-start="opacity-0"
        x-transition:enter-end="opacity-100"
        x-transition:leave="transition duration-150 ease-in"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        x-on:click.outside="open = false" x-on:keydown.escape.window="open = false"
        class="fixed inset-0 z-50 flex items-center justify-center bg-slate-950/65 p-4 backdrop-blur-sm"
        role="dialog" aria-modal="true" x-bind:aria-labelledby="'task-{{ $task->id }}-title'"
    >
        <div 
            class="glass-modal w-full max-w-lg rounded-xl p-6 max-h-[85vh] overflow-y-auto" x-on:click.stop
            x-transition:enter="transition duration-300 ease-spring"
            x-transition:enter-start="opacity-0 scale-90 -rotate-1"
            x-transition:enter-end="opacity-100 scale-100 rotate-0"
            x-transition:leave="transition duration-150 ease-in"
            x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95"
        >    
            <h2 id="task-{{ $task->id }}-title" class="font-semibold mb-4">{{ $task->title }}</h2>

            <form method="POST" action="{{ route('tasks.update', [$task->project_id, $task]) }}" enctype="multipart/form-data" class="space-y-4" x-data="taskForm()" x-on:submit="validateFiles($event)">
                @csrf
                @method('PUT')
                <div>
                    <label class="block text-sm font-medium mb-1">Title</label>
                    <input name="title" type="text" value="{{ $task->title }}" required minlength="3" maxlength="255" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Description</label>
                    <textarea name="description" rows="3" maxlength="5000" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">{{ $task->description }}</textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium mb-1">Deadline</label>
                        <input name="deadline" type="date" value="{{ $task->deadline?->format('Y-m-d') }}" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
                    </div>
                    <div>
                        <label class="block text-sm font-medium mb-1">Status</label>
                        <select name="status" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
                            @foreach (\App\Models\Task::STATUSES as $s)
                                <option value="{{ $s }}" @selected($task->status === $s)>{{ str_replace('_', ' ', $s) }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium mb-1">Attachments (max 10 MB per file)</label>
                    <input name="attachments[]" type="file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.webp,.txt,.zip" x-on:change="validateFiles()" class="w-full text-sm">
                    <p x-show="filesError" x-text="filesError" class="mt-1 text-sm text-red-600" role="alert"></p>
                </div>

                <div class="flex justify-between pt-2">
                    <button type="button" x-on:click.stop="$dispatch('close-task-modal-{{ $task->id }}')" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Close</button>
                    <button type="submit" class="cursor-pointer rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">Save</button>
                </div>
            </form>

            @if ($task->attachments->count())
                <ul class="mt-4 space-y-2 border-t border-slate-200 pt-4 dark:border-slate-700">
                    @foreach ($task->attachments as $attachment)
                        <li class="flex items-center justify-between gap-3 text-xs">
                            <a href="{{ $attachment->url() }}" target="_blank" rel="noopener" class="min-w-0 truncate text-teal-700 hover:underline dark:text-teal-300">{{ $attachment->original_name }} ({{ $attachment->humanSize() }})</a>
                            <form method="POST" action="{{ route('tasks.attachments.destroy', [$task->project_id, $task, $attachment]) }}" onsubmit="return confirm('Delete this attachment?');">@csrf @method('DELETE')<button type="submit" class="text-rose-700 hover:underline dark:text-rose-300">Delete</button></form>
                        </li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('tasks.destroy', [$task->project_id, $task]) }}" class="mt-3 pt-3 border-t border-slate-200 dark:border-[#334155]" onsubmit="return confirm('Delete this task?');">
                @csrf
                @method('DELETE')
                <button type="submit" class="cursor-pointer text-xs text-red-600 hover:underline">Delete task</button>
            </form>
        </div>
    </div>
    </template>
</div>
