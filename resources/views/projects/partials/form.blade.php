<div>
    <label class="block text-sm font-medium mb-1">Project name</label>
    <input name="name" type="text" value="{{ old('name', $project->name ?? '') }}" required minlength="3" maxlength="255" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
    @error('name') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-4">
    <label class="block text-sm font-medium mb-1">Description</label>
    <textarea name="description" rows="3" maxlength="5000" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">{{ old('description', $project->description ?? '') }}</textarea>
    @error('description') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
</div>

<div class="mt-4 grid grid-cols-2 gap-4">
    <div>
        <label class="block text-sm font-medium mb-1">Deadline</label>
        <input name="deadline" type="date" value="{{ old('deadline', optional($project->deadline ?? null)->format('Y-m-d')) }}" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
        @error('deadline') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
    <div>
        <label class="block text-sm font-medium mb-1">Status</label>
        <select name="status" class="w-full rounded-lg border-slate-300 dark:border-[#334155] dark:bg-[#0b1220]">
            @foreach (['planned' => 'Planned', 'active' => 'Active', 'on_hold' => 'On hold', 'completed' => 'Completed'] as $value => $label)
                <option value="{{ $value }}" @selected(old('status', $project->status ?? 'planned') === $value)>{{ $label }}</option>
            @endforeach
        </select>
        @error('status') <p class="mt-1 text-sm text-red-600">{{ $message }}</p> @enderror
    </div>
</div>

<div class="mt-6 flex justify-end gap-3">
    @if ($inModal ?? false)
        <button type="button" x-on:click="open = false" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Cancel</button>
    @else
        <a href="{{ isset($project) ? route('projects.show', $project) : route('projects.index') }}" class="cursor-pointer rounded-lg border border-slate-300 dark:border-[#334155] px-4 py-2 text-sm hover:bg-slate-50 dark:hover:bg-slate-800">Cancel</a>
    @endif
    <button type="submit" class="cursor-pointer rounded-lg bg-teal-600 px-4 py-2 text-sm font-medium text-white hover:bg-teal-700">{{ isset($project) ? 'Save changes' : 'Create project' }}</button>
</div>
