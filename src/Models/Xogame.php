<?php

namespace Xoxoday\Games\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Xogame extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $fillable = ['user_identifier','external_id','result','result_date','status'];
}
