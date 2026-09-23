<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Project extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'description', 'deadline', 'status', 'user_id'];

    protected $casts = [
        'deadline' => 'date',
    ];

    public const STATUSES = ['planned', 'active', 'on_hold', 'completed'];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function tasks(): HasMany
    {
        return $this->hasMany(Task::class)->orderBy('position');
    }

    public function progressPercentage(): int
    {
        $total = $this->tasks->count();
        if ($total === 0) {
            return 0;
        }

        $done = $this->tasks->where('status', 'done')->count();

        return (int) round(($done / $total) * 100);
    }
}
