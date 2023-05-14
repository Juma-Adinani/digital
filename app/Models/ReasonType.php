<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonType extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function reasons()
    {
        return $this->hasMany(ReasonForLeave::class);
    }
}
