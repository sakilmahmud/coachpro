<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserTestData extends Model
{
    use HasFactory;
    public $table = "user_test_data";
    protected $fillable = [
        'user_id',
        'question_id',
        'selected_answer_ids',
        'correct_answer_ids',
        'is_correct',
        'mock_test_id',
        'result_id'
        
    ];

    public function question()
    {
        return $this->belongsTo(Question::class);
    }

}
