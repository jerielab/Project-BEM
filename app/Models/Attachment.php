<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attachment extends Model
{
    protected $fillable = ['task_id', 'original_name', 'path', 'mime_type', 'size_bytes'];

    public function task(): BelongsTo
    {
        return $this->belongsTo(Task::class);
    }

    public function url(): string
    {
        return route('tasks.attachments.download', [
            'project' => $this->task->project_id,
            'task' => $this->task_id,
            'attachment' => $this->id,
        ]);
    }

    public function humanSize(): string
    {
        $bytes = $this->size_bytes;
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        while ($bytes >= 1024 && $i < count($units) - 1) {
            $bytes /= 1024;
            $i++;
        }

        return round($bytes, 1).' '.$units[$i];
    }
}
