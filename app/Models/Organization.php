<?php

namespace App\Models;

use App\Enums\UserAccountEnum;
use Dyrynda\Database\Support\CascadeSoftDeletes;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Organization extends Model
{
    use HasFactory, SoftDeletes, CascadeSoftDeletes;

    protected $cascadeDeletes = ['user', 'employees', 'buses', 'jobs', 'operators'];

    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'owner_user_id');
    }

    public function employees()
    {
        return $this->hasMany(Employee::class, 'organization_id');
    }

    public function buses()
    {
        return $this->hasMany(Bus::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    public function operators()
    {
        return $this->hasMany(Operator::class);
    }

    public static function findByToken($token)
    {
        if (!$token) {
            return null;
        }
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        if (!$user = User::find($data[0])) {
            return null;
        }

        if ($user->userAccount->account_type == UserAccountEnum::BUS_COOPERATIVE->value) {
            return $user->organization;
        } else {
            return self::find($user->operator->organization_id);
        }
    }
}
