<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Programme extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function departments()
    {
        return $this->belongsTo(Department::class, 'dept_id');
    }

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function supervisors()
    {
        return $this->hasOne(ClassSupervisor::class);
    }
}
