<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Course;
use App\Models\Department;
use App\Models\Grade;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // =====================
        // 1. BÖLÜMLER
        // =====================
        $bp = Department::create([
            'name'    => 'Bilgisayar Programcılığı',
            'code'    => 'BP',
            'faculty' => 'Meslek Yüksekokulu',
        ]);

        $ep = Department::create([
            'name'    => 'Elektrik Programı',
            'code'    => 'EP',
            'faculty' => 'Meslek Yüksekokulu',
        ]);

        // =====================
        // 2. ADMİN
        // =====================
        User::create([
            'name'          => 'Site Yöneticisi',
            'email'         => 'admin@okul.edu.tr',
            'password'      => Hash::make('password'),
            'role'          => 'admin',
            'department_id' => null,
        ]);

        // =====================
        // 3. ÖĞRETMENLer
        // =====================
        $teacher1 = User::create([
            'name'               => 'Ahmet Yılmaz',
            'email'              => 'ahmet.yilmaz@okul.edu.tr',
            'password'           => Hash::make('password'),
            'role'               => 'teacher',
            'department_id'      => $bp->id,
            'title'              => 'Dr. Öğr. Üyesi',
            'office_room'        => 'B-204',
            'office_hours'       => 'Salı 10:00-12:00, Perşembe 14:00-16:00',
            'institution_email'  => 'ahmet.yilmaz@okul.edu.tr',
            'biography'          => 'Web teknolojileri ve yazılım mühendisliği alanlarında çalışmaktadır.',
        ]);

        $teacher2 = User::create([
            'name'               => 'Fatma Demir',
            'email'              => 'fatma.demir@okul.edu.tr',
            'password'           => Hash::make('password'),
            'role'               => 'teacher',
            'department_id'      => $ep->id,
            'title'              => 'Öğr. Gör.',
            'office_room'        => 'A-101',
            'office_hours'       => 'Pazartesi 09:00-11:00',
            'institution_email'  => 'fatma.demir@okul.edu.tr',
            'biography'          => 'Elektrik devre analizi ve güç sistemleri alanında uzmanlaşmıştır.',
        ]);

        // =====================
        // 4. DERSLER
        // =====================

        // BP Dersleri
        $course1 = Course::create([
            'course_code'    => 'BP101',
            'course_name'    => 'Programlamaya Giriş',
            'akts'           => 5,
            'credits'        => 3,
            'semester'       => 1,
            'department_id'  => $bp->id,
            'teacher_id'     => $teacher1->id,
            'theory_hours'   => 2,
            'practice_hours' => 2,
            'vize_weight'    => 40,
            'final_weight'   => 60,
            'proje_weight'   => 0,
            'passing_grade'  => 50,
        ]);

        $course2 = Course::create([
            'course_code'    => 'BP202',
            'course_name'    => 'İnternet Programcılığı II',
            'akts'           => 4,
            'credits'        => 2,
            'semester'       => 2,
            'department_id'  => $bp->id,
            'teacher_id'     => $teacher1->id,
            'theory_hours'   => 2,
            'practice_hours' => 2,
            'vize_weight'    => 30,
            'final_weight'   => 50,
            'proje_weight'   => 20,
            'passing_grade'  => 50,
        ]);

        // EP Dersleri
        $course3 = Course::create([
            'course_code'    => 'EP101',
            'course_name'    => 'Devre Analizi',
            'akts'           => 5,
            'credits'        => 3,
            'semester'       => 1,
            'department_id'  => $ep->id,
            'teacher_id'     => $teacher2->id,
            'theory_hours'   => 3,
            'practice_hours' => 1,
            'vize_weight'    => 40,
            'final_weight'   => 60,
            'proje_weight'   => 0,
            'passing_grade'  => 50,
        ]);

        // =====================
        // 5. ÖĞRENCİLER
        // =====================
        $student1 = User::create([
            'name'           => 'Talha Kaya',
            'email'          => 'talha@okul.edu.tr',
            'password'       => Hash::make('password'),
            'role'           => 'student',
            'department_id'  => $bp->id,
            'student_number' => '2024010001',
        ]);

        $student2 = User::create([
            'name'           => 'Zeynep Arslan',
            'email'          => 'zeynep@okul.edu.tr',
            'password'       => Hash::make('password'),
            'role'           => 'student',
            'department_id'  => $bp->id,
            'student_number' => '2024010002',
        ]);

        $student3 = User::create([
            'name'           => 'Mehmet Çelik',
            'email'          => 'mehmet@okul.edu.tr',
            'password'       => Hash::make('password'),
            'role'           => 'student',
            'department_id'  => $ep->id,
            'student_number' => '2024020001',
        ]);

        // =====================
        // 6. DERS KAYITLARI (pivot)
        // =====================
        // BP öğrencileri → sadece BP dersleri
        $student1->courses()->attach([$course1->id, $course2->id]);
        $student2->courses()->attach([$course1->id, $course2->id]);

        // EP öğrencisi → sadece EP dersi
        $student3->courses()->attach([$course3->id]);

        // =====================
        // 7. NOTLAR
        // =====================
        Grade::create(['user_id' => $student1->id, 'course_id' => $course1->id, 'vize' => 75, 'final' => 80, 'proje' => null]);
        Grade::create(['user_id' => $student1->id, 'course_id' => $course2->id, 'vize' => 60, 'final' => null, 'proje' => 70]);
        Grade::create(['user_id' => $student2->id, 'course_id' => $course1->id, 'vize' => 45, 'final' => 55, 'proje' => null]);
        Grade::create(['user_id' => $student3->id, 'course_id' => $course3->id, 'vize' => 88, 'final' => 92, 'proje' => null]);
    }
}
