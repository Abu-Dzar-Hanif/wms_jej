<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class InboundRequest extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "inbound_request";
    protected $guarded = ["id"];
}
