<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserAccess extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "user_access";
    protected $guarded = ["id"];

    public function Menu()
    {
        return $this->belongsTo(Menu::class,'menu_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
