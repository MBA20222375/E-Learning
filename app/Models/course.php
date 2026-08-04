<?php

namespace App\Models;

use App\Models\User;
use App\Models\Section; // ✅
use App\Models\Lesson;  // ✅
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $table = 'courses';

    protected $fillable = [
        'title',
        'description',
        'price',
        'image',
        'is_published',
        'instructor_id',
        'category_id',
        'short_description',
         'what_you_learn',
        'level',
        'requirements',
        'who_is_this_for',
        'duration_hours',
        'duration_minutes',
            'language',

    ];

    protected $casts = [
        'is_published' => 'boolean',
        'price'        => 'decimal:2',
    ];

    public function instructor()
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function enrollments()
    {
        return $this->hasMany(Enrollment::class);
    }

    public function sections()
    {
        return $this->hasMany(Section::class)->orderBy('order');
    }

    public function lessons()
    {
        return $this->hasManyThrough(Lesson::class, Section::class);
    }
}
