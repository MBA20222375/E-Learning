<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Section;
class Lesson extends Model  // ✅ Capital L
{
    protected $table = 'lessons';

    protected $fillable = [
        'title',
        'type',
        'video_path',
        'pdf_path',
        'content',
        'duration',
        'order',
        'section_id',
        'is_free_preview',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }
    public function completedByUsers()
{
    return $this->hasMany(LessonUser::class);
}

public function isCompletedBy($userId): bool
{
    return $this->completedByUsers()->where('user_id', $userId)->where('is_completed', true)->exists();
}
}
