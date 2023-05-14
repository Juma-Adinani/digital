<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ReasonForLeave extends Model
{
    use HasFactory;
    public $table = 'reasons_for_leave';
    protected $guarded = [];

    public function student_leaves()
    {
        return $this->hasMany(StudentLeave::class);
    }

    public function students()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function reason_types()
    {
        return $this->belongsTo(ReasonType::class, 'reason_type_id');
    }
}
