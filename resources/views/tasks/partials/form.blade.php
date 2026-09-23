
<div
    x-data="taskForm()"
    x-on:open-task-modal.window="open = true"
    x-show="open" x-cloak
    x-transition:enter="transition duration-200 ease-out"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition duration-150 ease-in"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    x-on:click.outside="open = false" x-on:keydown.escape.window="open = false"
    class="fixed inset-0 z-50 flex items-center justify-center bg-black/40 p-4"
    role="dialog" aria-modal="true" aria-labelledby="new-task-title"
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
        <h2 id="new-task-title" class="font-semibold mb-4">New task</h2>

        <form method="POST" action="{{ route('tasks.store', $project) }}" enctype="multipart/form-data" class="space-y-4" x-on:submit="validateFiles($event)">
            @csrf
            <div>
                <label class="block text-sm font-medium mb-1">Title</label>
                <input name="title" type="text" required minlength="3" maxlength="255" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
                @error('title') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Description</label>
                <textarea name="description" rows="3" maxlength="5000" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]"></textarea>
            </div>
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-sm font-medium mb-1">Deadline</label>
                    <input name="deadline" type="date" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
                </div>
                <div>
                    <label class="block text-sm font-medium mb-1">Status</label>
                    <select name="status" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
                        @foreach (\App\Models\Task::STATUSES as $s)
                            <option value="{{ $s }}" @selected($s === 'todo')>{{ str_replace('_', ' ', $s) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div>
                <label class="block text-sm font-medium mb-1">Attachments (max 10 MB per file)</label>
                <input name="attachments[]" type="file" multiple accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.png,.jpg,.jpeg,.webp,.txt,.zip" x-on:change="validateFiles($event)" class="w-full text-sm">
                <p x-show="filesError" x-text="filesError" class="mt-1 text-sm text-red-600" role="alert"></p>
                @error('attachments.*') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
            </div>

            <div class="flex justify-end gap-3 pt-2">
                <button type="button" x-on:click="open = false" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Cancel</button>
                <button type="submit" class="cursor-pointer rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">Add task</button>
            </div>
        </form>
    </div>
</div>
