<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'lavel',
        'explanation',
        'question',
        'explaination',
        'course_id',
        'mock_test_id',
        'question_type'
    ];

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }

    public function usertestdata()
{
    return $this->hasMany(UserTestData::class);
}
public function answers()
    {
        return $this->hasMany(Answer::class,'questions_id','id');
    }
}
