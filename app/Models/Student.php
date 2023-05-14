<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function programmes()
    {
        return $this->belongsTo(Programme::class, 'program_id');
    }

    public function levels()
    {
        return $this->belongsTo(EducationLevel::class, 'level_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function reasons()
    {
        return $this->hasMany(ReasonForLeave::class);
    }
}
