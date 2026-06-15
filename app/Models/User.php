<?php

namespace App\Models;

use Database\Factories\UserFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'department_id',
        'student_number',
        'title',
        'office_room',
        'office_hours',
        'institution_email',
        'biography',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
        ];
    }

    // =====================
    //  İLİŞKİLER
    // =====================

    // Öğrencinin bölümü
    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // Öğrencinin kayıtlı olduğu dersler
    // Öğrencinin kayıtlı olduğu dersler
    public function courses()
    {
        return $this->belongsToMany(Course::class, 'course_user')
                    // Aşağıdaki satıra devamsızlık sütunlarını ekledik:
                    ->withPivot(['status', 'harf_notu', 'vize', 'final', 'absences_count', 'student_limit'])
                    ->withTimestamps();
    }

    // Öğretmenin verdiği dersler
    public function teachingCourses()
    {
        return $this->hasMany(Course::class, 'teacher_id');
    }

    // Öğrencinin notları
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // =====================
    //  YARDIMCI METODLAR
    // =====================

    // Rol kontrolü kolaylaştırıcılar
    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isTeacher(): bool
    {
        return $this->role === 'teacher';
    }

    public function isStudent(): bool
    {
        return $this->role === 'student';
    }

    // Öğretmenin tam unvanlı adı — Örn: "Dr. Öğr. Üyesi Ahmet Yılmaz"
    public function getFullTitleAttribute(): string
    {
        return trim("{$this->title} {$this->name}");
    }
}