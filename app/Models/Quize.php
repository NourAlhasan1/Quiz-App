<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
class Quize extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'duration',
        'mark',
        'date',
        'course_id',
        'user_id'

    ];


    /**
     * Get all of the comments for the Quize
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function questions(): HasMany
    {
        return $this->hasMany(Question::class);
    }
    public function user()
    {
    return $this->belongsTo(User::class);
    }

    public function studentAnswer()
    {
        return $this->belongsTo(studentAnswer::class);
    }

    public function mark(){
        return $this->hasOne(mark::class,'quiz_id');
    }
    public function students()
    {
        return $this->belongsToMany(student::class, 'student_quiz');
    }


}
