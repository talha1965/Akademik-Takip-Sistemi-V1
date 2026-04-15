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
        $request->validate([
            'user_id'   => 'required|exists:users,id',
            'course_id' => 'required|exists:courses,id',
            'vize'      => 'nullable|numeric|min:0|max:100',
            'proje'     => 'nullable|numeric|min:0|max:100',
            'final'     => 'nullable|numeric|min:0|max:100',
        ]);

        $updateData = array_filter($request->only(['vize', 'proje', 'final']), function($value) {
            return $value !== null;
        });

        if (empty($updateData)) {
            return back()->with('error', 'En az bir not alanı girmelisiniz!');
        }

        $grade = Grade::updateOrCreate(
            ['user_id' => $request->user_id, 'course_id' => $request->course_id],
            $updateData
        );

        $student = User::find($request->user_id);
        $course = Course::find($request->course_id);
        $student->notify(new GradeResultPublished($course, $grade));

        return back()->with('success', 'Notlar başarıyla güncellendi!');
    }

    /**
     * 2. ÖĞRENCİ DETAY
     */
    public function studentDetails($id)
    {
        $student = User::with('courses')->findOrFail($id);
        $grades = Grade::where('user_id', $id)->get()->keyBy('course_id');
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
            'value'     => 'nullable|numeric|min:0|max:100'
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
     */
    public function storeCourse(Request $request)
    {
        $request->validate(['course_name' => 'required|string|max:255']);
        Course::create([
            'course_name' => $request->course_name,
            'course_code' => 'BLG-' . rand(100, 999), 
            'akts'        => 5 
        ]);
        return back()->with('success', 'Ders başarıyla eklendi!');
    }

    /**
     * 6. ÖDEV TAMAMLANDI İŞLEMİ (TARİH KİLİTLİ)
     */
    public function toggleAssignment(Request $request, $id)
    {
        $user = Auth::user();
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
                ->update([
                    'is_completed' => !$exists->is_completed,
                    'updated_at' => now()
                ]);
        } else {
            DB::table('assignment_student')->insert([
                'assignment_id' => $id,
                'user_id' => $user->id,
                'is_completed' => true,
                'created_at' => now(),
                'updated_at' => now(),
            ]);
        }

        return back()->with('success', 'Ödev teslim durumu güncellendi.');
    }

    /**
     * 7. DERS KAYIT EKRANI
     */
    public function showCourseSelection()
    {
        $allCourses = Course::all();
        $myCourses = auth()->user()->courses()->pluck('courses.id')->toArray();
        return view('course-selection', compact('allCourses', 'myCourses'));
    }

    public function enrollCourses(Request $request)
    {
        auth()->user()->courses()->sync($request->courses ?? []);
        return back()->with('success', 'Ders kaydınız güncellendi!');
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
        Course::findOrFail($id)->delete();
        return back()->with('success', 'Ders silindi!');
    }

    /**
     * 9. DEVAMSIZLIK YÖNETİMİ
     */
    public function updateAbsence(Request $request, $courseId)
    {
        $user = auth()->user();
        $course = $user->courses()->where('courses.id', $courseId)->first();

        if ($course) {
            if ($request->has('student_limit')) {
                $user->courses()->updateExistingPivot($courseId, ['student_limit' => max(1, $request->student_limit)]);
            } elseif ($request->has('action')) {
                $count = $course->pivot->absences_count;
                $newCount = ($request->action === 'increment') ? $count + 1 : max(0, $count - 1);
                $user->courses()->updateExistingPivot($courseId, ['absences_count' => $newCount]);
            }
        }
        return redirect()->route('dashboard');
    }

    /**
     * 10. ÖĞRENCİ AKADEMİK ANALİZ (Karnem ve ESOGÜ Kuralları)
     */
    public function academicAnalysis()
    {
        $user = auth()->user();
        $grades = Grade::where('user_id', $user->id)->with('course')->get();
        
        $gno = 2.10; // Öğrencinin GNO'su (Dinamik yapılabilir)

        $analysisData = [];
        foreach ($grades as $grade) {
            $analysisData[$grade->course_id] = \App\Services\AcademicService::calculateAcademicStatus($grade, $grade->course, $gno);
        }

        return view('student.analysis', compact('grades', 'analysisData', 'gno'));
    }

    public function courseDetails()
    {
        $courses = auth()->user()->courses;
        return view('student.courses', compact('courses'));
    }

    public function assignmentDetails()
    {
        $user = auth()->user();
        $assignments = Assignment::with(['course', 'students' => function($query) use ($user) {
            $query->where('user_id', $user->id);
        }])->orderBy('due_date', 'asc')->get();

        return view('student.assignments', compact('assignments'));
    }

    /**
     * 11. ÖĞRETMEN DEĞERLENDİRME KRİTERİ AYARLARI
     */
    public function courseSettings($id)
    {
        $course = Course::findOrFail($id);
        return view('teacher.settings', compact('course'));
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
            return back()->with('error', 'Ağırlıkların toplamı tam %100 olmalıdır! Şu anki toplam: %' . $total);
        }

        $course = Course::findOrFail($id);
        $course->update($request->only(['vize_weight', 'proje_weight', 'final_weight', 'passing_grade']));

        return back()->with('success', 'Ders değerlendirme kuralları başarıyla güncellendi!');
    }
}