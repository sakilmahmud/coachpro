<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    use HasFactory;

    protected $fillable = [
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
        return $this->hasMany(Access::class, 'id');
    }
}
