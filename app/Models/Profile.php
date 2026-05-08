<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [

    'name',
    'email',
     'password',
      'phone',
      'role',
    'experience',
     'image',
     'about',
      'address',
    'twitter',
     'facebook',
     'instagram',
     'linkedin',
];
}
