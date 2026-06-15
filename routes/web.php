<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use App\Models\User;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\ExamController;
use App\Http\Controllers\DgsCalculatorController;

// ANA SAYFA
Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD YÖNLENDİRİCİSİ
Route::get('/dashboard', function () {
    $user = auth()->user();

    if ($user->role === 'admin') {
        return redirect()->route('admin.panel');
    }

    if ($user->role === 'teacher') {
        return redirect()->route('teacher.panel');
    }

    // Öğrenci Verileri
    // Öğrenci Verileri
    $grades = \App\Models\Grade::where('user_id', $user->id)->with('course')->get();

    // 1. GÜVENLİK FİLTRESİ: Öğrencinin kayıtlı olduğu derslerin ID'lerini alıyoruz
    $enrolledCourseIds = $user->courses()->pluck('courses.id');

    // 2. Sadece bu derslere ait ödevleri çekiyoruz
    $assignments = \App\Models\Assignment::whereIn('course_id', $enrolledCourseIds)
        ->with(['course', 'students' => function($query) use ($user) {
            $query->where('users.id', $user->id);
        }])->orderBy('due_date', 'asc')->get();

    return view('dashboard', compact('grades', 'assignments'));
})->middleware(['auth', 'verified'])->name('dashboard');


// GİRİŞ ZORUNLU ORTAK İŞLEMLER
Route::middleware('auth')->group(function () {
    // BİLDİRİM OKUMA ROTASI (GET yerine POST yaptık)
    Route::post('/bildirim/{id}/oku', function($id) {
        $notification = auth()->user()->notifications()->find($id);
        if($notification) {
            $notification->markAsRead();
        }
        return back();
    })->name('notification.read');

    // PROFİL VE MESAJLAR
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.read');

    // SADECE ÖĞRENCİ İŞLEMLERİ
    Route::post('/assignment/{id}/toggle', [AcademicController::class, 'toggleAssignment'])->name('assignment.toggle');
    Route::post('/course/{id}/absence', [AcademicController::class, 'updateAbsence'])->name('course.absence');
    Route::get('/akademik-analiz', [AcademicController::class, 'academicAnalysis'])->name('student.analysis');
    Route::get('/derslerim-detay', [AcademicController::class, 'courseDetails'])->name('student.courses');
    Route::get('/odevler-detay', [AcademicController::class, 'assignmentDetails'])->name('student.assignments');
    Route::get('/sinav-programi', [ExamController::class, 'studentIndex'])->name('student.exams');
    Route::get('/dgs-hesapla', [DgsCalculatorController::class, 'index'])->name('dgs.index');
    
    // DERS SEÇİM ROTALARI (Tekil Hataları Çözen Kısım)
    Route::get('/ders-secimi', [AcademicController::class, 'showCourseSelection'])->name('course.selection');
    Route::post('/ders-secimi', [AcademicController::class, 'enrollCourses'])->name('course.enroll');

    // SADECE ÖĞRETMEN İŞLEMLERİ
    Route::middleware([\App\Http\Middleware\TeacherMiddleware::class])->group(function () {

       Route::get('/teacher-panel', function () {
            $courses = Course::where('teacher_id', auth()->id())->get(); // Öğretmen sadece kendi derslerini görür
            $students = User::where('role', 'student')->where('department_id', auth()->user()->department_id)->get();
            $departments = \App\Models\Department::all(); // 1. BURAYI EKLEDİK
            
            return view('teacher-panel', compact('courses', 'students', 'departments')); // 2. DEPARTMENTS EKLEDİK
        })->name('teacher.panel');

        // YÜZDELİK AYARLARI ROTALARI
        Route::get('/teacher/course/settings', [AcademicController::class, 'courseSettings'])->name('teacher.course.settings');
        Route::put('/teacher/course/{id}/settings', [AcademicController::class, 'updateGradingRules'])->name('teacher.update.rules');

        Route::get('/teacher/student/{id}', [AcademicController::class, 'studentDetails'])->name('teacher.student.details');
        Route::post('/teacher/student/grade-update', [AcademicController::class, 'updateGradeInline'])->name('teacher.student.grade.update');
        Route::post('/grade-store', [AcademicController::class, 'store'])->name('grade.store');

        Route::post('/course-store', [AcademicController::class, 'storeCourse'])->name('course.store');
        Route::put('/course-update/{id}', [AcademicController::class, 'updateCourse'])->name('course.update');
        Route::delete('/course-delete/{id}', [AcademicController::class, 'deleteCourse'])->name('course.delete');
        Route::post('/assignment-store', [AcademicController::class, 'storeAssignment'])->name('assignment.store');

        Route::get('/teacher/sinavlar', [ExamController::class, 'teacherIndex'])->name('teacher.exams');
        Route::post('/teacher/sinav-ekle', [ExamController::class, 'store'])->name('teacher.exam.store');
        Route::delete('/teacher/sinav/{id}', [ExamController::class, 'destroy'])->name('teacher.exam.destroy');
    });

    // SADECE ADMİN İŞLEMLERİ
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/admin-panel', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.panel');
        Route::patch('/admin/user/{id}/role', [\App\Http\Controllers\AdminController::class, 'updateRole'])->name('admin.user.role');
    });
});

require __DIR__ . '/auth.php';