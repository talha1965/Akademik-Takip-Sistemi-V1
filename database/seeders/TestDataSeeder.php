<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Department;
use App\Models\Course;
use App\Models\Grade;
use App\Models\Assignment;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Str;

class TestDataSeeder extends Seeder
{
    public function run(): void
    {
        $departments = Department::all();

        if ($departments->isEmpty()) {
            $this->command->error('Sistemde hiç bölüm bulunamadı!');
            return;
        }

        $this->command->info('Gerçekçi test simülasyonu başlatılıyor...');

        $firstNames = ['Ahmet', 'Mehmet', 'Ayşe', 'Fatma', 'Mustafa', 'Zeynep', 'Ali', 'Elif', 'Burak', 'Merve', 'Emre', 'Ceren', 'Hasan', 'Esra', 'Can'];
        $lastNames = ['Yılmaz', 'Kaya', 'Demir', 'Çelik', 'Şahin', 'Yıldız', 'Öztürk', 'Aydın', 'Özdemir', 'Arslan', 'Doğan', 'Kılıç', 'Aslan', 'Çetin'];

        // Gerçekçi ders havuzları (Bölüm adına göre seçilecek)
        $coursePools = [
            'bilgisayar' => ['Algoritma ve Programlama', 'Veri Tabanı Yönetim Sistemleri', 'Nesne Yönelimli Programlama', 'Web Tasarımı', 'İşletim Sistemleri', 'Veri Yapıları', 'Yazılım Mühendisliği'],
            'yazılım' => ['Algoritma ve Programlama', 'Veri Tabanı Yönetim Sistemleri', 'Nesne Yönelimli Programlama', 'Web Tasarımı', 'İşletim Sistemleri', 'Veri Yapıları', 'Yazılım Mühendisliği'],
            'makine' => ['Makine Elemanları', 'Termodinamik', 'İmalat Yöntemleri', 'Bilgisayar Destekli Çizim', 'Malzeme Bilimi', 'Akışkanlar Mekaniği', 'Statik'],
            'elektrik' => ['Devre Analizi', 'Sayısal Tasarım', 'Elektromanyetik', 'Güç Sistemleri', 'Mikrodenetleyiciler', 'Sinyaller ve Sistemler', 'Elektronik Devreler'],
            'işletme' => ['Genel Muhasebe', 'Pazarlama İlkeleri', 'Mikro İktisat', 'Yönetim ve Organizasyon', 'İnsan Kaynakları', 'Finansal Yönetim', 'Ticaret Hukuku'],
            'mimarlık' => ['Mimari Tasarım', 'Yapı Bilgisi', 'Mimarlık Tarihi', 'Statik ve Mukavemet', 'Şehircilik İlkeleri', 'Bilgisayar Destekli Tasarım', 'Yapı Malzemeleri'],
            // Eğer bölüm adı bunlara uymazsa kullanılacak jenerik dersler:
            'genel' => ['Mesleki Matematik', 'İş Sağlığı ve Güvenliği', 'İletişim Becerileri', 'Araştırma Yöntemleri', 'Sektörel Proje', 'Kalite Yönetim Sistemleri', 'Girişimcilik']
        ];

        foreach ($departments as $deptIndex => $department) {
            
            // --- 1. GERÇEKÇİ HOCA OLUŞTUR ---
            $hocaAd = "Prof. Dr. " . $firstNames[array_rand($firstNames)] . " " . $lastNames[array_rand($lastNames)];
            $teacher = User::firstOrCreate(
                ['email' => "hoca{$department->id}@ats.com"],
                [
                    'name' => $hocaAd,
                    'password' => Hash::make('password'),
                    'role' => 'teacher',
                    'department_id' => $department->id,
                ]
            );

            // --- 2. BÖLÜME UYGUN DERSLERİ BELİRLE ---
            $deptNameLower = mb_strtolower($department->name);
            $selectedPool = $coursePools['genel']; // Varsayılan

            foreach ($coursePools as $key => $pool) {
                if ($key !== 'genel' && Str::contains($deptNameLower, $key)) {
                    $selectedPool = $pool;
                    break;
                }
            }

            // Havuzdan rastgele 5 ders seç
            shuffle($selectedPool);
            $selectedCourses = array_slice($selectedPool, 0, 5);
            $createdCourses = [];

            // --- 3. DERSLERİ OLUŞTUR ---
            foreach ($selectedCourses as $courseIndex => $courseName) {
                $createdCourses[] = Course::firstOrCreate(
                    ['course_name' => $courseName, 'department_id' => $department->id],
                    [
                        'course_code' => strtoupper(substr($department->name, 0, 3)) . (101 + $courseIndex),
                        'akts' => rand(3, 6),
                        'credits' => rand(2, 4),
                        'quota' => 60,
                        'semester' => rand(1, 2),
                        'theory_hours' => rand(2, 4),
                        'practice_hours' => rand(0, 2),
                        'teacher_id' => $teacher->id,
                        'vize_weight' => 40,
                        'proje_weight' => 0,
                        'final_weight' => 60,
                        'passing_grade' => 50,
                    ]
                );
            }

            // --- 4. BÖLÜME 3 TANE GERÇEKÇİ ÖĞRENCİ OLUŞTUR VE DERSLERE KAYDET ---
            for ($s = 1; $s <= 3; $s++) {
                $ogrenciAd = $firstNames[array_rand($firstNames)] . " " . $lastNames[array_rand($lastNames)];
                $student = User::firstOrCreate(
                    ['email' => "ogrenci{$department->id}_{$s}@ats.com"],
                    [
                        'name' => $ogrenciAd,
                        'password' => Hash::make('password'),
                        'role' => 'student',
                        'department_id' => $department->id,
                        'student_number' => "2026" . str_pad($department->id, 2, '0', STR_PAD_LEFT) . str_pad($s, 2, '0', STR_PAD_LEFT),
                    ]
                );

                // Her öğrenciyi o bölümün 5 dersine de kaydet ve not/ödev gir
                foreach ($createdCourses as $course) {
                    
                    // Derse Kayıt ve Rastgele Devamsızlık
                    $student->courses()->syncWithoutDetaching([
                        $course->id => [
                            'absences_count' => rand(0, 4), // 0 ile 4 hafta arası devamsızlık
                            'student_limit' => 4,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ]
                    ]);

                    // Rastgele Sınav Notları (Bazen yüksek, bazen düşük)
                    Grade::updateOrCreate(
                        ['user_id' => $student->id, 'course_id' => $course->id],
                        [
                            'vize' => rand(30, 100),
                            'final' => rand(40, 100)
                        ]
                    );

                    // Derse Gerçekçi Ödev Ata (Sadece 1. ve 3. dersler için ödev olsun ki liste dolup taşmasın)
                    if ($course->id % 2 != 0) {
                        $assignment = Assignment::firstOrCreate(
                            ['course_id' => $course->id, 'title' => "Dönem Sonu Araştırma Projesi"],
                            [
                                'description' => "Derste işlenen konuları kapsayan, minimum 5 sayfalık detaylı araştırma raporu hazırlanacaktır.",
                                'due_date' => Carbon::now()->addDays(rand(3, 14)),
                            ]
                        );

                        // Ödevi öğrenciye tanımla (Bazıları yapılmış olsun, bazıları beklesin)
                        DB::table('assignment_user')->updateOrInsert(
                            ['assignment_id' => $assignment->id, 'user_id' => $student->id],
                            ['is_completed' => rand(0, 1) == 1, 'created_at' => now(), 'updated_at' => now()]
                        );
                    }
                }
            }
        }

        $this->command->info('✅ Simülasyon tamamlandı! Gerçekçi veriler sisteme işlendi.');
    }
}