<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\Certificates;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;


/**
 * @OA\Schema(
 *      schema="User",
 *      required={"name","email","password","profile","role","age"},
 *      @OA\Property(
 *          property="id",
 *         type="integer",
 *         format="int64"
 *      ),
 *      @OA\Property(
 *          property="name",
 *         type="string",
 *      ),
 *      @OA\Property(
 *          property="email",
 *         type="string",
 *         format="email"
 *      ),
 *      @OA\Property(
 *          property="password",
 *         type="string",
 *          format="password"
 *      ),
 *      @OA\Property(
 *          property="profile",
 *         type="string",
 *      ),
 *      @OA\Property(
 *          property="role",
 *         type="string",
 *      ),
 *      @OA\Property(
 *          property="age",
 *         type="string",
 *      ),
 *          
 * 
 *    
 * )
 */
class User extends Authenticatable
{
    use HasFactory, HasApiTokens, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'profile',
        'role',
        'age',
        'phone',
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

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function certificates () {
        return $this->hasMany(Certificates::class);
    }
}
