<?php

namespace App\Models;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Laratrust\Traits\HasRolesAndPermissions;
use Laratrust\Contracts\LaratrustUser;
use Illuminate\Database\Eloquent\Casts\Attribute;

class User extends Authenticatable implements LaratrustUser
{
    use HasRolesAndPermissions;
    use HasApiTokens;
    use HasFactory;
    use Notifiable;
    use SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'first_name',
        'last_name',
        'email',
        'password',
        'CNIC',
        'gender',
        'date_of_birth'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $dates = ['date_of_birth'];

    public function networkAssociates()
    {
        return $this->hasOne(NetworkAssociate::class, 'user_id', 'id');
    }

    public function employee()
    {
        return $this->hasOne(Employee::class);
    }

    public function tasks()
    {
        return $this->hasMany(TaskMember::class, 'user_id', 'id');
    }

    protected function profilePictureUrl(): Attribute
    {
        return new Attribute(
            get: fn ($value) => asset('uploads/employees/user-dummy-img.jpg')
        );
    }

    public static function getUserNameByID($id)
    {
        return self::where('id', $id)->value('name');
    }
}
