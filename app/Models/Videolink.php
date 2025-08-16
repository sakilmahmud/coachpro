<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videolink extends Model
{
    use HasFactory;
    public $table = "video_link";
    protected $fillable = [
        'subject_id',
        'topic',
        'link'
    ];
}
