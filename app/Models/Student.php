<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'id_number', 'school_id', 'f_name', 'm_name', 'l_name', 'email', 'program',
    ];

    public function attendances()
    {
        return $this->hasMany(Attendance::class, 'school_id', 'school_id');
    }
}