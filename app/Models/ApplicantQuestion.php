<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicantQuestion extends Model
{
    use SoftDeletes;
    use HasFactory;

     /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'applicant_id',
        'question_id',
        'answer', 
        'isCorrect'
    ];
}
