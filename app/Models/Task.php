<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Task extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'description', 'deadline', 'status', 'position', 'project_id', 'user_id'];

    protected $casts = [
        'deadline' => 'date',
    ];

    public const STATUSES = ['todo', 'in_progress', 'done'];

    public function project(): BelongsTo
    {
        return $this->belongsTo(Project::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(Attachment::class);
    }

    public function isOverdue(): bool
    {
        return $this->deadline !== null
            && $this->status !== 'done'
            && $this->deadline->isBefore(today());
    }
}
