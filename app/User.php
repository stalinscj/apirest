<?php

namespace App;

use Laravel\Passport\HasApiTokens;
use App\Transformers\UserTransformer;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable, HasApiTokens, SoftDeletes;

    protected $table = 'users';

    protected $dates = ['deleted_at'];

    public $transformer = UserTransformer::class;

    const USER_VERIFIED = '1';
    const USER_NOT_VERIFIED = '0';

    const USER_ADMIN = 'true';
    const USER_REGULAR = 'false';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'verified',
        'verification_token',
        'admin',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'verification_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function setNameAttribute($name) {
        $this->attributes['name'] = strtolower($name);
    }
    
    public function getNameAttribute($name) {
        return  ucwords($name);
    }

    public function setEmailAttribute($email) {
        $this->attributes['email'] = strtolower($email);
    }

    public function isVerified()
    {
        return $this->verified == User::USER_VERIFIED;
    }

    public function isAdmin()
    {
        return $this->admin == User::USER_ADMIN;
    }

    public static function generateVerificationToken()
    {
        return str_random(40);
    }

}
