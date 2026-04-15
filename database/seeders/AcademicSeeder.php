<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Course;
use App\Models\Grade;
use App\Models\User;

class AcademicSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    
    public function run(): void
    {
        // 1. Önce bir ders ekleyelim
    $course = Course::create([
        'course_code' => 'BPR202',
        'course_name' => 'İnternet Programcılığı II',
        'akts' => 5
    ]);

    // 2. Mevcut kullanıcını (kendini) bul ve bu derse not ekle
    $user = User::first(); 

    Grade::create([
        'user_id' => $user->id,
        'course_id' => $course->id,
        'vize' => 85,
        'final' => 90
    ]);
    }
}
