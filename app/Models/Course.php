<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'year',
        'semester',
    ];


    public function users()
    {
        return $this->belongsToMany('App\Models\User', 'assigned', 'course_id', 'user_id');
    }

}
