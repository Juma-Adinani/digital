<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeanFaculty extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function faculties()
    {
        return $this->belongsTo(Faculty::class, 'faculty_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
