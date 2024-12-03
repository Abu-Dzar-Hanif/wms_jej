<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Models\UserAccess;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasApiTokens,HasFactory, Notifiable,SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $table = 'users';
    protected $guarded = ['id'];

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

    public function UserAccess()
    {
        return $this->hasMany(UserAccess::class,'user_id');
    }

    public function hasPermissionByName($menuName, $permission)
    {
        // Ambil akses user untuk menu berdasarkan nama
        $menu = Menu::where('name', $menuName)->first();

        if (!$menu) {
            // Menu dengan nama ini tidak ditemukan
            return false;
        }

        $access = $this->UserAccess->where('menu_id', $menu->id)->first();
        // dd($access);
        if (!$access) {
            // Tidak ada akses untuk menu ini
            return false;
        }

        // Periksa apakah izin diberikan
        return $access->$permission == 1;
    }
}
