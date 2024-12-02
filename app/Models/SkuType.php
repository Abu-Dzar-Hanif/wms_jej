<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkuType extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "sku_type";
    protected $guarded = ["id"];
}
