<?php

namespace App;


use App\Notifications\AdminAttackNotification;
use App\Notifications\ClientAttackNotification;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements JWTSubject
{
    use Notifiable, SoftDeletes;

    const TEXT_SEARCHABLE = [
        'first_name',
        'last_name',
        'email',
    ];

    const SPECIFIC_SEARCHABLE = [
        'id',
        'role',
        'status',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    const CAN_ADMIN_SEARCH = true;
    const CAN_USER_SEARCH = true;
    const CAN_PUBLIC_SEARCH = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name', 'last_name', 'email', 'password', 'role', 'status',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token', 'created_at', 'updated_at'
    ];

    protected $with = ['websites'];

    public function getJWTIdentifier() {
        return $this->getKey();
    }
    
    public function getJWTCustomClaims() {
        return [];
    }

    public function sendAdminAttackNotification($data)
    {
        $this->notify(new AdminAttackNotification($data));
    }

    public function sendClientAttackNotification($data)
    {
        $this->notify(new ClientAttackNotification($data));
    }

    public function websites() {
        return $this->hasMany('App\Website');
    }
}
