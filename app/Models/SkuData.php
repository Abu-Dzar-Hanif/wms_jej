<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class SkuData extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "sku_data";
    protected $guarded = ["id"];
}
