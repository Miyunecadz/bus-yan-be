<?php

namespace App\Models;

use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class);
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public static function findByToken($token)
    {
        if (!$token) {
            return null;
        }
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        return self::where('owner_user_id', $data[0])->first();
    }
}
