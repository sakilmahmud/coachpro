<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Videolink extends Model
{
    use HasFactory;
    public $timestamps = false;
    public $table = "video_link";
    protected $fillable = [
        'subject_id',
        'topic',
        'link'
    ];
}
