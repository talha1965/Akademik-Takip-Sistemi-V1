<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use App\Models\Course;
use App\Models\Grade;

class GradeResultPublished extends Notification
{
    use Queueable;

    public $course;
    public $grade;

    // Bildirim tetiklendiğinde Ders ve Not bilgilerini alıyoruz
    public function __construct(Course $course, Grade $grade)
    {
        $this->course = $course;
        $this->grade = $grade;
    }

    // Bildirimin nereye gideceğini seçiyoruz (Biz sadece Veritabanına kaydedeceğiz)
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    // Veritabanına kaydedilecek bilgileri JSON formatında paketliyoruz
    public function toArray(object $notifiable): array
    {
        return [
            'type'        => 'grade',
            'course_id'   => $this->course->id,
            'course_name' => $this->course->course_name,
            'message'     => $this->course->course_name . ' dersinin notları açıklandı/güncellendi!',
            'vize'        => $this->grade->vize,
            'final'       => $this->grade->final,
        ];
    }
}