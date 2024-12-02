<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Customer extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "customer";
    protected $guarded = ["id"];
    protected $casts = [
        'phone' => 'encrypted',
        'address' => 'encrypted'
    ];
}
