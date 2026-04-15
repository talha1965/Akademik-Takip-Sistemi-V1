<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Grade extends Model
{
    protected $fillable = ['user_id', 'course_id', 'vize', 'proje', 'final'];

    // Bu notun sahibi olan öğrenci (User)
    public function student(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Bu notun ait olduğu ders (Course)
    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    // OOP Yeteneği: Ortalama hesaplama fonksiyonu
    public function calculateAverage()
    {
        return ($this->vize * 0.4) + ($this->final * 0.6);
    }
}
