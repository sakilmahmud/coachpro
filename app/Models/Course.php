<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'logo',
    ];

    public function flashQuestions()
    {
        return $this->hasMany(FlashQuestion::class);
    }

    public function mockTests()
    {
        return $this->hasMany(MockTest::class);
    }
    
    public function questions()
    {
        return $this->hasMany(Question::class);
    }
}
