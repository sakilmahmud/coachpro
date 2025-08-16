<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FlashQuestion extends Model
{
    use HasFactory;
    public $table = "flash_question";
    protected $fillable = [
        'subject',
        'question',
        'answer'
    ];

}
