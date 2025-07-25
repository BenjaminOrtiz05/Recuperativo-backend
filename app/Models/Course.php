<?php

// app/Models/Course.php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    protected $fillable = [
        'name',
        'description',
        'category',
        'duration',
        'active',
        'image',
    ];
}
