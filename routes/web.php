<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Models\Course;
use App\Models\User;
use App\Http\Controllers\AcademicController;
use App\Http\Controllers\MessageController;

// ANA SAYFA
Route::get('/', function () {
    return view('welcome');
});

// DASHBOARD YÖNLENDİRİCİSİ (TRAFİK POLİSİ)
Route::get('/dashboard', function () {
    $user = auth()->user();

    // 1. Admin Yönlendirmesi
    if ($user->role === 'admin') {
        return redirect()->route('admin.panel');
    }

    // 2. Öğretmen Yönlendirmesi
    if ($user->role === 'teacher') {
        return redirect()->route('teacher.panel');
    }

    // 3. Öğrenci Paneli Veri Hazırlığı
    $grades = \App\Models\Grade::where('user_id', $user->id)->with('course')->get();
    
    $assignments = \App\Models\Assignment::with(['course', 'students' => function($query) use ($user) {
        $query->where('user_id', $user->id);
    }])->orderBy('due_date', 'asc')->get();

    return view('dashboard', compact('grades', 'assignments'));
    
})->middleware(['auth', 'verified'])->name('dashboard');


// GİRİŞ ZORUNLU ORTAK İŞLEMLER (Herkes İçin)
Route::middleware('auth')->group(function () {
    
    // --- PROFİL İŞLEMLERİ ---
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // --- MESAJLAŞMA MERKEZİ ---
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::patch('/messages/{id}/read', [MessageController::class, 'markAsRead'])->name('messages.read');

    // --- BİLDİRİM OKUMA ---
    Route::post('/notifications/mark-as-read/{id}', function ($id) {
        $notification = auth()->user()->notifications()->find($id);
        if($notification) {
            $notification->markAsRead();
        }
        return back();
    })->name('notification.read');

    // --- SADECE ÖĞRENCİ İŞLEMLERİ ---
    Route::post('/assignment/{id}/toggle', [AcademicController::class, 'toggleAssignment'])->name('assignment.toggle');
    Route::get('/ders-secimi', [AcademicController::class, 'showCourseSelection'])->name('course.selection');
    Route::post('/ders-secimi', [AcademicController::class, 'enrollCourses'])->name('course.enroll');
    Route::post('/course/{id}/absence', [AcademicController::class, 'updateAbsence'])->name('course.absence');
    Route::get('/akademik-analiz', [AcademicController::class, 'academicAnalysis'])->name('student.analysis');
    Route::get('/derslerim-detay', [AcademicController::class, 'courseDetails'])->name('student.courses');
    Route::get('/odevler-detay', [AcademicController::class, 'assignmentDetails'])->name('student.assignments');

    // --- SADECE ÖĞRETMEN İŞLEMLERİ (Özel Korumalı Bölge) ---
    Route::middleware([\App\Http\Middleware\TeacherMiddleware::class])->group(function () {
        
        // Öğretmen Ana Paneli
        Route::get('/teacher-panel', function () {
            $courses = Course::all();
            $students = User::where('role', 'student')->get();
            return view('teacher-panel', compact('courses', 'students'));
        })->name('teacher.panel');  

        // Ders Ayarları (Kriter Belirleme)
        Route::get('/teacher/course/{id}/settings', [AcademicController::class, 'courseSettings'])->name('teacher.course.settings');
        Route::put('/teacher/course/{id}/settings', [AcademicController::class, 'updateGradingRules'])->name('teacher.update.rules');

        // Öğrenci Not Yönetimi
        Route::get('/teacher/student/{id}', [AcademicController::class, 'studentDetails'])->name('teacher.student.details');
        Route::post('/teacher/student/grade-update', [AcademicController::class, 'updateGradeInline'])->name('teacher.student.grade.update');
        Route::post('/grade-store', [AcademicController::class, 'store'])->name('grade.store');

        // Ders ve Ödev Yönetimi
        Route::post('/course-store', [AcademicController::class, 'storeCourse'])->name('course.store');
        Route::put('/course-update/{id}', [AcademicController::class, 'updateCourse'])->name('course.update');
        Route::delete('/course-delete/{id}', [AcademicController::class, 'deleteCourse'])->name('course.delete');
        Route::post('/assignment-store', [AcademicController::class, 'storeAssignment'])->name('assignment.store');
    });

    // --- SADECE ADMİN İŞLEMLERİ (God Mode) ---
    Route::middleware([\App\Http\Middleware\AdminMiddleware::class])->group(function () {
        Route::get('/admin-panel', [\App\Http\Controllers\AdminController::class, 'index'])->name('admin.panel');
    });
});

require __DIR__ . '/auth.php';