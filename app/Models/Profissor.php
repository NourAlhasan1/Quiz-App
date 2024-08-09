<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Profissor extends Model
{
    use HasFactory;
    protected $fillable = [

        'name',
        'phone',
        'national_num',
        'user_id'
    ];


}
