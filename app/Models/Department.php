<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'code', 'faculty'];

    // Bu bölümdeki öğretmenler
    public function teachers()
    {
        return $this->hasMany(User::class, 'department_id')
                    ->where('role', 'teacher');
    }

    // Bu bölümdeki öğrenciler
    public function students()
    {
        return $this->hasMany(User::class, 'department_id')
                    ->where('role', 'student');
    }

    // Bu bölüme ait dersler
    public function courses()
    {
        return $this->hasMany(Course::class);
    }
}
