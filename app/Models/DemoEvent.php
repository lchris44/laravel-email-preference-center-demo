<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DemoEvent extends Model
{
    protected $fillable = [
        'event_class',
        'user_name',
        'category',
        'action',
        'via',
        'frequency',
        'item_count',
    ];
}
