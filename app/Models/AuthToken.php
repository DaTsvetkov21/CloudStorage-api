<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AuthToken extends Model
{
    protected $primaryKey = null;
    const UPDATED_AT = null;
    public $incrementing = false;
    protected $fillable = [
        'user_id',
        'auth_token',
        'outdated'
    ];
}
