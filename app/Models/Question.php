<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        "quize_id",
        'mark',
        'text',
    ];

    public function quiz()
    {
        return $this->belongsTo(Quize::class);
    }




    public function choices()
    {
        return $this->hasMany(Choice::class,'question_id');
    }
    /**
     * Get the user that owns the Question
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(Quize::class);
    }

    public function studentAnswer()
    {
        return $this->belongsTo(studentAnswer::class);
    }


}
