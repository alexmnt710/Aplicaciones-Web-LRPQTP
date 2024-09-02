<?php

namespace App\Models;


// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;
    
    protected $primaryKey = 'userId';
    protected $fillable = ['userName', 'userPassword', 'userTypeId'];

    public function userType()
    {
        return $this->belongsTo(UserType::class, 'userTypeId', 'userTypeId');
    }

    public function clases()
    {
        return $this->hasMany(Clase::class, 'users_userId', 'userId');
    }

    public function pagos()
    {
        return $this->hasMany(Pago::class, 'clases_users_userId', 'userId');
    }
}
