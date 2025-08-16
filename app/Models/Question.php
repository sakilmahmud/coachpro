<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    use HasFactory;
    protected $fillable = [
        'subject',
        'title',
        'lavel',
        'explanation',
        'question',
        'explaination'
    ];

    public function usertestdata()
{
    return $this->hasMany(UserTestData::class);
}
public function answers()
    {
        return $this->hasMany(Answer::class,'questions_id','id');
    }
}
