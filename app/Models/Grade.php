<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = ['user_id', 'course_id', 'vize', 'proje', 'final'];

    // =====================
    //  İLİŞKİLER
    // =====================

    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // =====================
    //  HESAPLAMA METODLARI
    // =====================

    /**
     * Ham ortalama hesapla (dersin ağırlıklarına göre)
     * Örn: Vize %40 + Proje %0 + Final %60
     */
    public function calculateAverage(): ?float
    {
        $course = $this->course;

        if (!$course || $this->final === null) {
            return null;
        }

        $vizeWeight  = $course->vize_weight  / 100;
        $finalWeight = $course->final_weight / 100;
        $projeWeight = $course->proje_weight / 100;

        $average = 0;
        $average += ($this->vize  ?? 0) * $vizeWeight;
        $average += ($this->final ?? 0) * $finalWeight;
        $average += ($this->proje ?? 0) * $projeWeight;

        return round($average, 2);
    }

    /**
     * Harf notunu döndür (AA, BA, BB ... FF)
     */
    public function getLetterGrade(): string
    {
        $average = $this->calculateAverage();

        if ($average === null) {
            return 'Girilemedi';
        }

        $scale = $this->course?->grading_scale 
                 ?? Course::defaultGradingScale();

        foreach ($scale as $row) {
            if ($average >= $row['min'] && $average <= $row['max']) {
                return $row['letter'];
            }
        }

        return 'FF';
    }

    /**
     * GPA katsayısını döndür (4.00 üzerinden)
     */
    public function getGpaPoint(): float
    {
        $average = $this->calculateAverage();

        if ($average === null) {
            return 0.00;
        }

        $scale = $this->course?->grading_scale 
                 ?? Course::defaultGradingScale();

        foreach ($scale as $row) {
            if ($average >= $row['min'] && $average <= $row['max']) {
                return $row['gpa'];
            }
        }

        return 0.00;
    }

    /**
     * Dersi geçip geçmediği
     */
    public function isPassing(): bool
    {
        $average      = $this->calculateAverage();
        $passingGrade = $this->course?->passing_grade ?? 50;

        return $average !== null && $average >= $passingGrade;
    }

    /**
     * Finalde kaç alması gerekiyor? (Geçmek için gereken final notu)
     * Formül: (GeçmeNotu - Vize * vizeWeight - Proje * projeWeight) / finalWeight
     */
    public function requiredFinalGrade(): ?float
    {
        $course = $this->course;

        if (!$course || $this->vize === null) {
            return null;
        }

        $passingGrade = $course->passing_grade;
        $vizeWeight   = $course->vize_weight  / 100;
        $finalWeight  = $course->final_weight / 100;
        $projeWeight  = $course->proje_weight / 100;

        $alreadyEarned = ($this->vize  * $vizeWeight) 
                       + (($this->proje ?? 0) * $projeWeight);

        if ($finalWeight == 0) {
            return null;
        }

        $required = ($passingGrade - $alreadyEarned) / $finalWeight;

        // 0 ile 100 arasında sınırla
        return round(max(0, min(100, $required)), 1);
    }
}
