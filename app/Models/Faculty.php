<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Faculty extends Model
{
    use HasFactory;

    public $timestamps = true;

    public function departments()
    {
        return $this->hasMany(Department::class);
    }

    public function deans()
    {
        return $this->hasMany(DeanFaculty::class);
    }
}
