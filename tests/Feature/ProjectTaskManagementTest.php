<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class ProjectTaskManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_an_authenticated_user_can_create_a_project(): void
    {
        
        $user = $this->makeUser(['email_verified_at' => now()]);

        $response = $this->actingAs($user)->post(route('projects.store'), [
            'name' => 'Website Portfolio',
            'description' => 'Membangun portofolio baru.',
            'deadline' => now()->addWeek()->toDateString(),
            'status' => 'active',
        ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('projects', ['user_id' => $user->id, 'name' => 'Website Portfolio', 'status' => 'active']);
    }

    public function test_task_attachment_is_stored_within_the_ten_megabyte_limit(): void
    {
        Storage::fake('private');
        
        $user = $this->makeUser(['email_verified_at' => now()]);
        $project = Project::factory()->for($user)->create();
        $file = UploadedFile::fake()->create('brief.pdf', 512, 'application/pdf');

        $response = $this->actingAs($user)->post(route('tasks.store', $project), [
            'title' => 'Tulis brief proyek',
            'description' => 'Versi pertama.',
            'deadline' => now()->addDay()->toDateString(),
            'status' => 'todo',
            'attachments' => [$file],
        ]);

        $response->assertRedirect(route('projects.show', $project));
        $task = Task::where('title', 'Tulis brief proyek')->firstOrFail();
        $this->assertDatabaseHas('attachments', ['task_id' => $task->id, 'original_name' => 'brief.pdf']);
        $this->assertTrue(Storage::disk('private')->exists('tasks/'.$task->id.'/'.$file->hashName()));
    }

    public function test_today_is_not_marked_as_overdue(): void
    {
        
        $user = $this->makeUser();
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($user)->for($project)->create(['deadline' => today(), 'status' => 'todo']);

        $this->assertFalse($task->isOverdue());
    }

    public function test_attachment_download_requires_project_ownership(): void
    {
        Storage::fake('private');
        
        $owner = $this->makeUser(['email_verified_at' => now()]);
        
        $otherUser = $this->makeUser(['email_verified_at' => now()]);
        $project = Project::factory()->for($owner)->create();
        $task = Task::factory()->for($owner)->for($project)->create();
        $path = 'tasks/'.$task->id.'/brief.pdf';
        Storage::disk('private')->put($path, 'file contents');
        $attachment = $task->attachments()->create([
            'original_name' => 'brief.pdf',
            'path' => $path,
            'mime_type' => 'application/pdf',
            'size_bytes' => 13,
        ]);

        $this->actingAs($otherUser)
            ->get(route('tasks.attachments.download', [$project, $task, $attachment]))
            ->assertForbidden();

        $this->actingAs($owner)
            ->get(route('tasks.attachments.download', [$project, $task, $attachment]))
            ->assertOk()
            ->assertHeader('content-disposition', 'attachment; filename=brief.pdf');
    }

    public function test_deleting_a_project_removes_attachment_files(): void
    {
        Storage::fake('private');
        
        $user = $this->makeUser(['email_verified_at' => now()]);
        $project = Project::factory()->for($user)->create();
        $task = Task::factory()->for($user)->for($project)->create();
        $path = 'tasks/'.$task->id.'/brief.pdf';
        Storage::disk('private')->put($path, 'file contents');
        $task->attachments()->create([
            'original_name' => 'brief.pdf',
            'path' => $path,
            'mime_type' => 'application/pdf',
            'size_bytes' => 13,
        ]);

        $this->actingAs($user)->delete(route('projects.destroy', $project))->assertRedirect(route('projects.index'));

        $this->assertFalse(Storage::disk('private')->exists($path));
    }

    public function test_deadline_filter_only_shows_matching_tasks(): void
    {
        
        $user = $this->makeUser(['email_verified_at' => now()]);
        $project = Project::factory()->for($user)->create();
        Task::factory()->for($user)->for($project)->create(['title' => 'Tugas terlambat', 'deadline' => now()->subDay(), 'status' => 'todo']);
        Task::factory()->for($user)->for($project)->create(['title' => 'Tugas minggu depan', 'deadline' => now()->addWeek(), 'status' => 'todo']);

        $this->actingAs($user)->get(route('projects.show', [$project, 'deadline' => 'overdue']))
            ->assertOk()
            ->assertSee('Tugas terlambat')
            ->assertDontSee('Tugas minggu depan');
    }

    public function test_dragging_a_task_recalculates_positions_in_the_destination_column(): void
    {
        
        $user = $this->makeUser(['email_verified_at' => now()]);
        $project = Project::factory()->for($user)->create();
        $first = Task::factory()->for($user)->for($project)->create(['status' => 'todo', 'position' => 0]);
        $second = Task::factory()->for($user)->for($project)->create(['status' => 'todo', 'position' => 1]);

        $this->actingAs($user)->postJson(route('tasks.reorder', [$project, $second]), ['status' => 'todo', 'position' => 0])
            ->assertOk()
            ->assertJsonPath('ok', true);

        $this->assertDatabaseHas('tasks', ['id' => $second->id, 'status' => 'todo', 'position' => 0]);
        $this->assertDatabaseHas('tasks', ['id' => $first->id, 'status' => 'todo', 'position' => 1]);
    }

    private function makeUser(array $attributes = []): User
    {
        $created = User::factory()->create($attributes);

        return User::query()->findOrFail($created->getKey());
    }
}
