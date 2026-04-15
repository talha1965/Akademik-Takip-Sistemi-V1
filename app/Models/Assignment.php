<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    use HasFactory;

    /**
     * Toplu atamaya (Mass Assignment) izin verilen sütunlar.
     * Güvenlik için sadece formdan gelecek verileri buraya yazıyoruz.
     */
    protected $fillable = [
        'course_id',
        'title',
        'description',
        'due_date',
    ];

    /**
     * Veritabanından çekilen verilerin otomatik tür dönüşümü (Casting).
     * Bu sayede due_date sütunu düz metin (string) yerine
     * Carbon (Tarih) objesi olarak gelir ve üzerinde işlem yapmak kolaylaşır.
     */
    protected $casts = [
        'due_date' => 'datetime',
    ];

    /**
     * İLİŞKİ: Bir ödev (Assignment), tek bir derse (Course) aittir.
     * (One-to-Many'nin tersi)
     */
    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * İLİŞKİ: Bir ödev birden fazla öğrenci (User) tarafından tamamlanabilir.
     * Many-to-Many ilişkisi (assignment_student ara tablosu üzerinden kurulur).
     */
    public function students()
    {
        return $this->belongsToMany(User::class, 'assignment_student')
                    ->withPivot('is_completed') // Ara tablodaki tamamlanma durumunu da getirir
                    ->withTimestamps();         // İşaretleme zamanını kaydeder
    }

    /**
     * OOP METODU: Ödevin teslim tarihinin geçip geçmediğini kontrol eder.
     * Arayüzde süresi geçen ödevleri kırmızı göstermek için kullanacağız.
     */
    public function isOverdue()
    {
        return $this->due_date->isPast();
    }
}