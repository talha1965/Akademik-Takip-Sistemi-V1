<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Course extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_name',
        'course_code',
        'akts',
        // Yeni eklenen değerlendirme kuralları
        'vize_weight',
        'final_weight',
        'proje_weight',
        'passing_grade',
        'grading_scale'
    ];

    // JSON verisini otomatik olarak PHP Array'ine çevirir
    protected $casts = [
        'grading_scale' => 'array',
    ];

    // ... (Mevcut ilişkilerin aynı kalacak) ...
    public function users()
    {
        return $this->belongsToMany(User::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }
}