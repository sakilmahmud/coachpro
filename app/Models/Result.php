<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Result extends Model
{
    use HasFactory;
    public $table = "results";
    protected $fillable = [
        'user_id',
        'course_id',
        'mock_test_id',
        'title',
        'subject',
        'attempt_id',
        'percentage',
        'correct_count',
        'incorrect_count',
        'unattempted_count'
    ];

    public function mockTest()
    {
        return $this->belongsTo(MockTest::class);
    }
}
