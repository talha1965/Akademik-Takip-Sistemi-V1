<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Grade;

class AcademicService
{
    /**
     * Varsayılan ESOGÜ Harf Notu Skalası
     */
    const DEFAULT_GRADING_SCALE = [
        'AA' => ['min' => 90, 'max' => 100, 'katsayi' => 4.0],
        'BA' => ['min' => 85, 'max' => 89.99, 'katsayi' => 3.5],
        'BB' => ['min' => 75, 'max' => 84.99, 'katsayi' => 3.0],
        'CB' => ['min' => 70, 'max' => 74.99, 'katsayi' => 2.5],
        'CC' => ['min' => 60, 'max' => 69.99, 'katsayi' => 2.0],
        'DC' => ['min' => 45, 'max' => 59.99, 'katsayi' => 1.5], // Şartlı Bölge
        'DD' => ['min' => 40, 'max' => 44.99, 'katsayi' => 1.0], // Şartlı Bölge
        'FF' => ['min' => 0,  'max' => 39.99, 'katsayi' => 0.0],
    ];

    /**
     * Not Hesaplama ve Durum Analizi Motoru
     */
    public static function calculateAcademicStatus(Grade $grade, Course $course, $studentGno = 2.00)
    {
        // 1. Notlar boşsa sıfır kabul et
        $vize = $grade->vize ?? 0;
        $final = $grade->final ?? 0;
        $proje = $grade->proje ?? 0; // Eğer proje notu tablosuna eklediysen

        // 2. Hocanın belirlediği yüzdelere göre Ağırlıklı Ortalama hesapla
        $ortalama = ($vize * ($course->vize_weight / 100)) + 
                    ($final * ($course->final_weight / 100)) + 
                    ($proje * ($course->proje_weight / 100));

        // 3. Harf Notunu Bul
        $harfNotu = 'FF';
        $katsayi = 0.0;
        $skala = $course->grading_scale ?? self::DEFAULT_GRADING_SCALE;

        foreach ($skala as $harf => $degerler) {
            if ($ortalama >= $degerler['min'] && $ortalama <= $degerler['max']) {
                $harfNotu = $harf;
                $katsayi = $degerler['katsayi'];
                break;
            }
        }

        // 4. ESOGÜ Durum (Geçti/Kaldı) Algoritması
        $durum = 'KALDI';
        $renk = 'red'; // Arayüz için renk kodu
        $mesaj = 'Başarısız.';

        if ($final < $course->passing_grade) {
            // FİNAL BARAJI KONTROLÜ
            $durum = 'KALDI';
            $harfNotu = 'FF'; // Final barajı geçilemediyse harf FF'e düşer
            $mesaj = 'Final barajını (' . $course->passing_grade . ') geçemediniz.';
        } elseif (in_array($harfNotu, ['AA', 'BA', 'BB', 'CB', 'CC'])) {
            // DOĞRUDAN GEÇİŞ
            $durum = 'GEÇTİ';
            $renk = 'emerald';
            $mesaj = 'Doğrudan geçtiniz.';
        } elseif (in_array($harfNotu, ['DC', 'DD'])) {
            // ŞARTLI GEÇİŞ (GNO KONTROLÜ DEVREYE GİRER)
            if ($studentGno >= 2.00) {
                $durum = 'ŞARTLI GEÇTİ';
                $renk = 'amber';
                $mesaj = 'GNO 2.00 ve üzeri olduğu için şartlı geçtiniz.';
            } else {
                $durum = 'KALDI (ORTALAMA YETERSİZ)';
                $renk = 'red';
                $mesaj = 'GNO 2.00 altında olduğu için DC/DD ile kaldınız. En az CC almalıydınız.';
            }
        }

        // Sonuçları paketleyip geri gönder
        return [
            'ortalama' => round($ortalama, 2),
            'harf_notu' => $harfNotu,
            'katsayi' => $katsayi,
            'durum' => $durum,
            'renk' => $renk,
            'mesaj' => $mesaj
        ];
    }
}