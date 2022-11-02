<?php

namespace Xoxoday\Games\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpinTheWheel extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_id','code_id','result','result_date','status'];
}
