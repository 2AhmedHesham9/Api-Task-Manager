<?php

namespace App\Models\Tasks;
use App\Models\User;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;

use Illuminate\Database\Eloquent\Model;

class Task extends Authenticatable implements JWTSubject
{

    protected $table='tasks';
    protected $fillable = [
        'title',
        'description',
        'deadline',
        'status',
        'created_by',
        'created_by',
        'user_id',


    ];

    public function user()
    {
        return $this->belongsTo(User::class,'id','user_id');

    }
    use HasFactory;


       // Rest omitted for brevity

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }
}
