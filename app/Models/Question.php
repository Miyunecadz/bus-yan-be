<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Question extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;
    
    protected $cascadeDeletes = ['answer'];

    protected $guarded = [];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function answer()
    {
        return $this->hasOne(Answer::class);
    }
}
