<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DosRecommendation extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function dof_recommendations(){
        return $this->belongsTo(DofRecommendation::class, 'dof_remarks_id');
    }
}
