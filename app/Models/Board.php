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

    protected $appends = [
        'text',
        'value',
    ];

    public function tasks()
    {
        return $this->hasMany('App\Models\Task', 'board_id', 'id');
    }

    public function getTextAttribute()
    {
        return $this->title;
    }

    public function getValueAttribute()
    {
        return $this->id;
    }
}
