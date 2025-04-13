<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PinCode extends Model
{
    protected $table = 'pin_codes';

    protected $fillable = [
        'purpose',
        'pin_code',
        'type',
    ];
}
