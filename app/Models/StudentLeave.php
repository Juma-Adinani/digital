<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StudentLeave extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function reasons()
    {
        return $this->belongsTo(ReasonForLeave::class, 'reason_id');
    }

    public function supervisor_recommendations()
    {
        return $this->hasOne(SupervisorRecommendation::class);
    }
}
