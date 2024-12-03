<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserWhAccess extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "user_access";
    protected $guarded = ["id"];

    public function Warehouse()
    {
        return $this->belongsTo(Warehouse::class,'warehouse_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
