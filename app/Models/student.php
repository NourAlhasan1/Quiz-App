<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class student extends Model
{
    use HasFactory;
    protected $fillable = [
        'uni_num',
        'user_id',
        'f_name',
        'l_name',
        's_name',
        'm_name',
        'number',
        'national_num'

    ];
    public function studentAnswer(){
        return $this->hasMany(studentAnswer::class);
    }
    public function mark(){
        return $this->hasOne(mark::class);
    }

    public function quizes(){
        return $this->belongsToMany( Quize::class);

    }
    public function exams()
    {
        return $this->belongsToMany(Quize::class, 'student_quiz');
    }
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
