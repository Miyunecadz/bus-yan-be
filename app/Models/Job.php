<?php

namespace App\Models;

use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['questions', 'applications'];

    protected $guarded = [];

    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    public function questions()
    {
        return $this->hasMany(Question::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }
}
