<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Access extends Model
{
    use HasFactory;
    public $table = "access_subject";
    protected $fillable = [
        'subject_id',
        'student_id'
    ];

   public function user()
  {
      return $this->belongsTo(User::class, 'id');
  }

  public function subject()
  {
      return $this->belongsTo(Subject::class, 'id');
  }
}
