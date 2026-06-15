<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Carbon\Carbon;

class Exam extends Model
{
    use HasFactory;

    protected $fillable = [
        'course_id',
        'teacher_id',
        'department_id',
        'exam_type',
        'exam_date',
        'start_time',
        'end_time',
        'classroom',
        'notes',
    ];

    protected $casts = [
        'exam_date' => 'date',
    ];

    // =====================
    //  İLİŞKİLER
    // =====================

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function teacher()
    {
        return $this->belongsTo(User::class, 'teacher_id');
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    // =====================
    //  YARDIMCI METODLAR
    // =====================

    // Sınav tipini Türkçe döndür
    public function getExamTypeLabelAttribute(): string
    {
        return match($this->exam_type) {
            'vize'       => 'Vize',
            'final'      => 'Final',
            'butunleme'  => 'Bütünleme',
            default      => 'Sınav',
        };
    }

    // Sınav geçti mi?
    public function isPast(): bool
    {
        return Carbon::parse($this->exam_date)->isPast();
    }

    // Kaç gün kaldı?
    public function daysUntil(): int
    {
        return (int) Carbon::now()->startOfDay()->diffInDays(
            Carbon::parse($this->exam_date)->startOfDay(),
            false
        );
    }
}
