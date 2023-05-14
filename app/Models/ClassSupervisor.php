<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ClassSupervisor extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function programmes()
    {
        return $this->belongsTo(Programme::class, 'program_id');
    }
}
