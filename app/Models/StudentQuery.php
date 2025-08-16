<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentQuery extends Model
{
    use HasFactory;
    public $table = "student_query";
    protected $fillable = [
        'student_name',
        'student_mail',
        'contry',
        'student_number',
        'student_querys'
    ];

}
