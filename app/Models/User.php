<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use App\Enums\UserAccountEnum;
use App\Models\Employee;
use App\Models\Organization;
use App\Models\UserAccount;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Crypt;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'email',
        'phone_number',
        'display_name',
        'password'
    ];

    protected $hidden = [
        'password'
    ];


    public function userAccount()
    {
        return $this->hasOne(UserAccount::class);
    }

    public function organization()
    {
        return $this->hasOne(Organization::class, 'owner_user_id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class, 'employee_id');
    }

    public function applications()
    {
        return $this->hasMany(Application::class, 'user_id');
    }

    public function scopeBusOperator($query)
    {
        $query->whereHas('userAccount', function ($subQuery) {
            $subQuery->where('account_role', UserAccountEnum::BUS_OPERATOR->value);
        });
    }

    public function scopeJobseeker($query)
    {
        $query->whereHas('userAccount', function ($subQuery) {
            $subQuery->where('account_role', UserAccountEnum::JOBSEEKER->value);
        });
    }

    public static function getUserByToken($token)
    {
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        return self::find($data[0]);
    }

    public static function getUserByRoleToken($role, $token)
    {
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        return self::whereHas('userAccount', function ($subQuery) use ($role) {
            $subQuery->where('account_role', $role);
        })
        ->find($data[0]);
    }

    public static function getOrganizationByToken($token)
    {
        $decryptedToken = Crypt::decrypt($token);
        $data = explode('-', $decryptedToken);

        $user =  self::busOperator()->with(['organization' => function ($query) {
            $query->with(['employees', 'buses', 'jobs.questions']);
        }])->find($data[0]);

        return $user->organization;
    }

    public function isAdmin()
    {
        return $this->userAccount->account_role == UserAccountEnum::ADMIN->value;
    }

    public function isBusOperator()
    {
        return $this->userAccount->account_role == UserAccountEnum::BUS_OPERATOR->value;
    }
}
