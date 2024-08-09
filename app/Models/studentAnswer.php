<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class studentAnswer extends Model
{
    use HasFactory;
    protected $fillable = [
        'uni_num',
        'quiz_id',
        'question_id',
        'choice_id'

    ];
    public function student(){
        return $this->belongsTo(student::class,'uni_num');
    }


    public function question(){
        return $this->hasOne(Question::class,'id');
    }

    public function choice(){
        return $this->hasOne(Choice::class);
    }

    public function quiz(){
        return $this->hasOne(Quize::class);
    }




}
