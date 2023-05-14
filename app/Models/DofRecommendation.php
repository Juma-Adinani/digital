<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DofRecommendation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function supervisor_recommendations()
    {
        return $this->belongsTo(SupervisorRecommendation::class, 's_remark_id');
    }

    public function dos_recommendations()
    {
        return $this->hasOne(DosRecommendation::class);
    }
}
