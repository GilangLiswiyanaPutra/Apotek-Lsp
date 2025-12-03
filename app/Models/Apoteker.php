<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Apoteker extends Model
{
    //
    // app/Models/Apoteker.php
    protected $fillable = [
        'nama', 'email', 'telepon', 'password',
    ];

    protected $hidden = [
        'password',
    ];
}
