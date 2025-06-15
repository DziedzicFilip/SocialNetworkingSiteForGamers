<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    protected $table = 'users';

    public $timestamps = false;

    protected $fillable = [
        'username', 'email', 'password_hash', 'avatar_url', 'bio', 'role', 'created_at', 'last_login'
    ];

    // Laravel domyślnie szuka 'password', więc musisz to nadpisać:
    public function getAuthPassword()
    {
        return $this->password_hash;
    }
}