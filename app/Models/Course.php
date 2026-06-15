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
        'credits',
        'semester',
        'department_id',
        'teacher_id',
        'theory_hours',
        'practice_hours',
        'vize_weight',
        'final_weight',
        'proje_weight',
        'passing_grade',
        'grading_scale',
        'quota', // Yeni Eklendi: Ders Kontenjanı
    ];

    protected $casts = [
        'grading_scale' => 'array',
    ];

    // =====================
    //  İLİŞKİLER
    // =====================

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function users()
    {
        return $this->belongsToMany(User::class)
                    ->withPivot('absences_count', 'student_limit')
                    ->withTimestamps();
    }
    
    public function students()
    {
        return $this->belongsToMany(User::class, 'course_user')
                    ->withPivot(['status', 'harf_notu', 'vize', 'final'])
                    ->withTimestamps();
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }

    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    // =====================
    //  YARDIMCI METODLAR
    // =====================

    /**
     * Devamsızlık limitini hesapla (YÖK: teorik %30, pratik %20)
     * Bir dönem 16 hafta varsayımıyla hesaplanır.
     */
    public function getAbsenceLimits(): array
    {
        $weeks = 16;
        return [
            'theory'   => (int) floor($this->theory_hours * $weeks * 0.30),
            'practice' => (int) floor($this->practice_hours * $weeks * 0.20),
        ];
    }

    /**
     * Varsayılan YÖK harf notu skalası
     */
    public static function defaultGradingScale(): array
    {
        return [
            ['min' => 90, 'max' => 100, 'letter' => 'AA', 'gpa' => 4.00],
            ['min' => 85, 'max' => 89,  'letter' => 'BA', 'gpa' => 3.50],
            ['min' => 75, 'max' => 84,  'letter' => 'BB', 'gpa' => 3.00],
            ['min' => 65, 'max' => 74,  'letter' => 'CB', 'gpa' => 2.50],
            ['min' => 55, 'max' => 64,  'letter' => 'CC', 'gpa' => 2.00],
            ['min' => 45, 'max' => 54,  'letter' => 'DC', 'gpa' => 1.50],
            ['min' => 35, 'max' => 44,  'letter' => 'DD', 'gpa' => 1.00],
            ['min' => 0,  'max' => 34,  'letter' => 'FF', 'gpa' => 0.00],
        ];
    }

    /**
     * Kalan Kontenjanı Dinamik Olarak Hesapla
     */
    public function getAvailableQuotaAttribute()
    {
        $maxQuota = $this->quota ?? 50; // Eğer veritabanında quota boşsa varsayılan 50 kabul et
        return $maxQuota - $this->students()->count();
    }
}