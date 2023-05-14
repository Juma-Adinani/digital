<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupervisorRecommendation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function student_leaves()
    {
        return $this->belongsTo(StudentLeave::class, 'leave_id');
    }

    public function dof_recommendations()
    {
        return $this->hasOne(DofRecommendation::class);
    }
}
