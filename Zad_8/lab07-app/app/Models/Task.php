<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Task extends Model
{
    protected $fillable = [
        'title',
        'description',
        'is_done',
        'priority',
    ];

    protected $casts = [
        'is_done' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
