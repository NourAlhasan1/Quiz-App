<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assigned extends Model
{
    use HasFactory;
    protected $fillable = [
        'year',
        'user_id',
        'course_id',
    ];
    public function quizzes()
    {
        return $this->hasMany(Quize::class);
    }
}
