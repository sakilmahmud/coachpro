<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'subject',
        'titel',
        'duration',
        'starting_date',
        'end_date',
        'explnation'
    ];
    public function access()
    {
        return $this->hasMany(User::class,'questions_id','id');
    }
    public function accessSubjects()
    {
        return $this->hasMany(Access::class, 'subject_id');
    }

    // New relationship to Course model
        public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function pdfs()
    {
        return $this->hasMany(Pdf::class, 'subject_id');
    }

    public function videolinks()
    {
        return $this->hasMany(Videolink::class, 'subject_id');
    }

    public function getEnrolledStudents()
    {
        return $this->hasManyThrough(User::class, Access::class, 'subject_id', 'id', 'id', 'student_id');
    }
}
