<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Board extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'sort',
    ];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'board_id', 'id');
    }
}
