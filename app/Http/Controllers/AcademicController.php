<?php

namespace App\Http\Controllers;

use App\Notifications\GradeResultPublished;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Models\Grade;
use App\Models\Course;
use App\Models\Assignment;
use App\Models\User;
use Carbon\Carbon;

class AcademicController extends Controller
{
    /**
     * 1. NOT EKLEME (Vize, Proje, Final)
     */
    public function store(Request $request)
    {
        // 1. Formdan gelen verileri doğruluyoruz
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'vize' => 'required|numeric|min:0|max:100',
            'final' => 'nullable|numeric|min:0|max:100',
        ]);

        $student = \App\Models\User::findOrFail($request->user_id);
        $course = \App\Models\Course::findOrFail($request->course_id);

        // GÜVENLİK DUVARI 1: Hoca sadece KENDİ dersine not girebilir
        if ($course->teacher_id !== auth()->id()) {
            return back()->withErrors(['Yetki Hatası' => 'Sadece kendi verdiğiniz derslere not girebilirsiniz!']);
        }

        // GÜVENLİK DUVARI 2: Öğrenci bu dersi GERÇEKTEN almış mı?
        if (!$student->courses->contains($course->id)) {
            return back()->withErrors(['Kayıt Hatası' => 'Bu öğrenci seçilen derse kayıtlı değil! Önce öğrencinin dersi seçmesi gerekir.']);
        }

        // Tüm güvenlik testlerinden geçtiyse notu kaydet/güncelle
        \App\Models\Grade::updateOrCreate(
            ['user_id' => $student->id, 'course_id' => $course->id],
            ['vize' => $request->vize, 'final' => $request->final]
        );

        return back()->with('success', 'Öğrencinin notu başarıyla sisteme işlendi!');
    }

    /**
     * 2. ÖĞRENCİ DETAY
     * Öğretmen sadece kendi derslerindeki öğrencileri görebilir.
     */
    public function studentDetails($id)
    {
        $teacher = auth()->user();
 
        $teacherCourseIds = Course::where('teacher_id', $teacher->id)->pluck('id');
 
        $isEnrolled = DB::table('course_user')
            ->where('user_id', $id)
            ->whereIn('course_id', $teacherCourseIds)
            ->exists();
 
        if (!$isEnrolled) {
            abort(403, 'Bu öğrenciye erişim yetkiniz yok.');
        }
 
        $student = User::with(['courses' => function ($q) use ($teacherCourseIds) {
            $q->whereIn('courses.id', $teacherCourseIds);
        }])->findOrFail($id);
 
        $grades = Grade::where('user_id', $id)
                       ->whereIn('course_id', $teacherCourseIds)
                       ->with('course')
                       ->get()
                       ->keyBy('course_id');
 
        return view('student-details', compact('student', 'grades'));
    }

    /**
     * 3. HIZLI NOT GÜNCELLEME
     */
    public function updateGradeInline(Request $request)
    {
        $request->validate([
            'user_id'   => 'required',
            'course_id' => 'required',
            'type'      => 'required|in:vize,proje,final',
            'value'     => 'nullable|numeric|min:0|max:100',
        ]);

        Grade::updateOrCreate(
            ['user_id' => $request->user_id, 'course_id' => $request->course_id],
            [$request->type => $request->value]
        );

        return back()->with('success', 'Not güncellendi!');
    }

    /**
     * 4. ÖDEV ATAMA
     */
    public function storeAssignment(Request $request)
    {
        $request->validate([
            'course_id'   => 'required|exists:courses,id',
            'title'       => 'required|string|max:255',
            'description' => 'required|string',
            'due_date'    => 'required|date|after:today',
        ]);

        Assignment::create($request->all());
        return back()->with('success', 'Yeni ödev başarıyla eklendi!');
    }

    /**
     * 5. DERS OLUŞTURMA
     * Öğretmen kendi bölümüne ders oluşturur.
     */
   /**
     * 5. DERS OLUŞTURMA
     * Öğretmen kendi bölümüne ders oluşturur.
     */
    public function storeCourse(Request $request)
    {
        $request->validate([
            'course_name' => 'required|string|max:255',
            'course_code' => 'required|string|max:50',
            'akts' => 'required|integer|min:1',
            'credits' => 'required|integer|min:1',
            'quota' => 'required|integer|min:1',
            'semester' => 'required|integer',
            'theory_hours' => 'required|integer|min:0',
            'practice_hours' => 'required|integer|min:0',
            'department_id' => 'required|exists:departments,id', // Doğrulama ekledik
        ]);

        Course::create([
            'course_name' => $request->course_name,
            'course_code' => strtoupper($request->course_code),
            'akts' => $request->akts,
            'credits' => $request->credits,
            'quota' => $request->quota,
            'semester' => $request->semester,
            'theory_hours' => $request->theory_hours,
            'practice_hours' => $request->practice_hours,
            'department_id' => $request->department_id, // Veritabanına kaydediyoruz
            'teacher_id' => auth()->id(), // Dersi ekleyen hocayı bağlıyoruz
        ]);

        return back()->with('success', 'Yeni ders başarıyla akademik havuza eklendi!');
    }

    /**
     * 6. ÖDEV TAMAMLANDI İŞLEMİ (TARİH KİLİTLİ)
     */
    public function toggleAssignment(Request $request, $id)
    {
        $user       = Auth::user();
        $assignment = Assignment::findOrFail($id);

        if (Carbon::parse($assignment->due_date)->endOfDay()->isPast()) {
            return back()->with('error', 'Bu ödevin teslim süresi dolduğu için üzerinde değişiklik yapamazsınız.');
        }

        $exists = DB::table('assignment_student')
            ->where('assignment_id', $id)
            ->where('user_id', $user->id)
            ->first();

        if ($exists) {
            DB::table('assignment_student')
                ->where('assignment_id', $id)
                ->where('user_id', $user->id)
                ->update(['is_completed' => !$exists->is_completed, 'updated_at' => now()]);
        } else {
            DB::table('assignment_student')->insert([
                'assignment_id' => $id,
                'user_id'       => $user->id,
                'is_completed'  => true,
                'created_at'    => now(),
                'updated_at'    => now(),
            ]);
        }

        return back()->with('success', 'Ödev teslim durumu güncellendi.');
    }

    /**
     * 7. DERS KAYIT EKRANI (TOPLU SEÇİM)
     */
   /**
     * 7. DERS KAYIT EKRANI (TOPLU SEÇİM)
     */
    public function showCourseSelection()
    {
        $user = auth()->user();

        // Öğrencinin bölümüne ait dersler
        $allCourses = \App\Models\Course::where('department_id', $user->department_id)->get();

        // Seçili derslerin ID'lerini dizi olarak alıyoruz (Blade'deki in_array için)
        $myCourses = $user->courses()->pluck('courses.id')->toArray();

        // 'student.course-selection' yerine sadece 'course-selection' yazıyoruz:
        return view('course-selection', compact('allCourses', 'myCourses'));
    }

    public function enrollCourses(Request $request)
    {
        $user = auth()->user();
        
        // Formdan gelen ders ID'leri (Hiç seçilmemişse boş dizi döner)
        $selectedCourseIds = $request->courses ?? [];

        // 1. AKTS Limit Kontrolü (Maksimum 30 AKTS)
        $selectedCourses = \App\Models\Course::whereIn('id', $selectedCourseIds)->get();
        $totalAkts = $selectedCourses->sum('akts');

        if ($totalAkts > 30) {
            return back()->with('error', "Maksimum 30 AKTS seçebilirsiniz. Sizin seçiminiz: {$totalAkts} AKTS.");
        }

        // 2. Kontenjan Kontrolü (Sadece YENİ eklenen dersler için kontrol yapmalıyız)
        $currentCourseIds = $user->courses->pluck('id')->toArray();
        $newCourseIds = array_diff($selectedCourseIds, $currentCourseIds);

        foreach ($newCourseIds as $courseId) {
            $course = $selectedCourses->where('id', $courseId)->first();
            if ($course && $course->available_quota <= 0) {
                return back()->with('error', "{$course->course_name} dersinin kontenjanı dolmuştur. Lütfen seçiminizi güncelleyin.");
            }
        }

        // 3. Veritabanına Kayıt (Senkronizasyon)
        // sync metodu listede olmayanları siler, olanları ekler.
        $syncData = [];
        foreach ($selectedCourseIds as $id) {
            $syncData[$id] = ['status' => 'active'];
        }
        
        $user->courses()->sync($syncData);

        return back()->with('success', 'Ders seçimleriniz başarıyla kaydedildi!');
    }

    /**
     * 8. DERS DÜZENLE/SİL
     */
    public function updateCourse(Request $request, $id)
    {
        $request->validate(['course_name' => 'required|string|max:255']);
        Course::findOrFail($id)->update(['course_name' => $request->course_name]);
        return back()->with('success', 'Ders güncellendi!');
    }

   public function deleteCourse($id)
    {
        $course = \App\Models\Course::findOrFail($id);

        // 1. Derse ait tüm verileri manuel olarak temizle
        \App\Models\Grade::where('course_id', $id)->delete();
        \App\Models\Assignment::where('course_id', $id)->delete();
        \Illuminate\Support\Facades\DB::table('course_user')->where('course_id', $id)->delete();

        // 2. KRİTİK NOKTA: SQLite'ın hatalı bağlantı aramasını geçici olarak durdur
        \Illuminate\Support\Facades\Schema::disableForeignKeyConstraints();

        // 3. Dersi şimdi güvenle sil
        $course->delete();

        // 4. Veritabanı güvenlik kontrollerini tekrar aktif et
        \Illuminate\Support\Facades\Schema::enableForeignKeyConstraints();

        return back()->with('success', 'Ders ve derse bağlı tüm veriler başarıyla silindi!');
    }

    /**
     * 9. DEVAMSIZLIK YÖNETİMİ
     */
    public function updateAbsence(Request $request, $courseId)
    {
        $user   = auth()->user();
        $course = $user->courses()->where('courses.id', $courseId)->first();

        if ($course) {
            if ($request->has('student_limit')) {
                $user->courses()->updateExistingPivot($courseId, [
                    'student_limit' => max(1, $request->student_limit),
                ]);
            } elseif ($request->has('action')) {
                $count    = $course->pivot->absences_count;
                $newCount = ($request->action === 'increment') ? $count + 1 : max(0, $count - 1);
                $user->courses()->updateExistingPivot($courseId, ['absences_count' => $newCount]);
            }
        }

        return redirect()->route('dashboard');
    }

    /**
     * 10. ÖĞRENCİ AKADEMİK ANALİZ
     * GNO artık dinamik hesaplanıyor.
     */
    public function academicAnalysis()
    {
        $user   = auth()->user();
        $grades = Grade::where('user_id', $user->id)->with('course')->get();

        // GNO: (AKTS × GPA katsayısı) toplamı / toplam AKTS
        $totalAkts   = 0;
        $totalPoints = 0;

        foreach ($grades as $grade) {
            if ($grade->course && $grade->calculateAverage() !== null) {
                $akts         = $grade->course->akts;
                $totalAkts   += $akts;
                $totalPoints += $grade->getGpaPoint() * $akts;
            }
        }

        $gno = $totalAkts > 0 ? round($totalPoints / $totalAkts, 2) : 0.00;

        $analysisData = [];
        foreach ($grades as $grade) {
            $analysisData[$grade->course_id] = \App\Services\AcademicService::calculateAcademicStatus(
                $grade,
                $grade->course,
                $gno
            );
        }

        return view('student.analysis', compact('grades', 'analysisData', 'gno'));
    }

    public function courseDetails()
    {
        $courses = auth()->user()->courses()->with('teacher')->get();
        return view('student.courses', compact('courses'));
    }

 public function assignmentDetails()
    {
        $user = auth()->user();
        
        // Öğrencinin aldığı derslerin ID'lerini bul
        $enrolledCourseIds = $user->courses()->pluck('courses.id');

        // Sadece öğrencinin aldığı derslerin ödevlerini getir
        $assignments = \App\Models\Assignment::whereIn('course_id', $enrolledCourseIds)
            ->with(['course', 'students' => function($query) use ($user) {
                $query->where('users.id', $user->id);
            }])
            ->orderBy('due_date', 'asc')
            ->get();

        return view('student.assignments', compact('assignments')); // View adın neyse o kalmalı
    }

    /**
     * 11. ÖĞRETMEN DEĞERLENDİRME KRİTERİ AYARLARI
     */
    public function courseSettings() // $id parametresini kaldırdık
    {
        $teacher = auth()->user();
        // Öğretmenin verdiği tüm dersleri çekiyoruz
        $courses = Course::where('teacher_id', $teacher->id)->get(); 
        
        return view('teacher.settings', compact('courses'));
    }

    public function updateGradingRules(Request $request, $id)
    {
        $request->validate([
            'vize_weight'   => 'required|numeric|min:0|max:100',
            'proje_weight'  => 'required|numeric|min:0|max:100',
            'final_weight'  => 'required|numeric|min:0|max:100',
            'passing_grade' => 'required|numeric|min:0|max:100',
        ]);

        $total = $request->vize_weight + $request->proje_weight + $request->final_weight;

        if ($total != 100) {
            return back()->with('error', "Ağırlıkların toplamı tam %100 olmalıdır! Şu anki toplam: %{$total}");
        }

        Course::findOrFail($id)->update(
            $request->only(['vize_weight', 'proje_weight', 'final_weight', 'passing_grade'])
        );

        return back()->with('success', 'Ders değerlendirme kuralları başarıyla güncellendi!');
    }
}