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
        'question_name',
        'selected_option',
        'is_correct',
        'attempt_id'
        
    ];
    public function question()
{
    return $this->belongsTo(Question::class);
}

}
