<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class mark extends Model
{
    use HasFactory;

    public function student(){
        return $this->belongsTo(student::class);
    }

    public function quiz(){
        return $this->belongsTo(Quize::class);
    }
}
