<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LessonUser extends Model
{
    protected $table = 'lesson_users';

    protected $fillable = [
        'user_id',
        'lesson_id',
        'is_completed',
    ];

    protected $casts = [
        'is_completed' => 'boolean',
    ];
}
