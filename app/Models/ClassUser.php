<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ClassUser extends Pivot
{
    protected $table = 'class_user';
    
    protected $casts = [
        'enrolled_at' => 'datetime',
    ];
}