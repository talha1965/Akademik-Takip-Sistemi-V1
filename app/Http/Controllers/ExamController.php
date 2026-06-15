<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Course;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Öğrenci: Kendi bölümünün sınav programını görür
     */
    public function studentIndex()
    {
        $user = auth()->user();

        $exams = Exam::where('department_id', $user->department_id)
                     ->with(['course', 'teacher'])
                     ->orderBy('exam_date', 'asc')
                     ->orderBy('start_time', 'asc')
                     ->get()
                     ->groupBy('exam_date'); // Tarihe göre grupla

        return view('student.exams', compact('exams'));
    }

    /**
     * Öğretmen: Kendi derslerinin sınav programını görür + ekler
     */
    public function teacherIndex()
    {
        $teacher = auth()->user();

        $exams = Exam::where('teacher_id', $teacher->id)
                     ->with('course')
                     ->orderBy('exam_date', 'asc')
                     ->get();

        $courses = Course::where('teacher_id', $teacher->id)->get();

        return view('teacher.exams', compact('exams', 'courses'));
    }

    /**
     * Öğretmen: Yeni sınav ekle
     */
    public function store(Request $request)
    {
        $request->validate([
            'course_id'  => 'required|exists:courses,id',
            'exam_type'  => 'required|in:vize,final,butunleme',
            'exam_date'  => 'required|date|after:today',
            'start_time' => 'required|date_format:H:i',
            'end_time'   => 'required|date_format:H:i|after:start_time',
            'classroom'  => 'required|string|max:100',
            'notes'      => 'nullable|string|max:500',
        ]);

        $teacher = auth()->user();
        $course  = Course::findOrFail($request->course_id);

        // Öğretmen sadece kendi dersine sınav ekleyebilir
        if ($course->teacher_id !== $teacher->id) {
            abort(403, 'Bu derse sınav ekleme yetkiniz yok.');
        }

        Exam::create([
            'course_id'     => $course->id,
            'teacher_id'    => $teacher->id,
            'department_id' => $teacher->department_id,
            'exam_type'     => $request->exam_type,
            'exam_date'     => $request->exam_date,
            'start_time'    => $request->start_time,
            'end_time'      => $request->end_time,
            'classroom'     => $request->classroom,
            'notes'         => $request->notes,
        ]);

        return back()->with('success', 'Sınav programı başarıyla eklendi!');
    }

    /**
     * Öğretmen: Sınav sil
     */
    public function destroy($id)
    {
        $exam = Exam::findOrFail($id);

        if ($exam->teacher_id !== auth()->id()) {
            abort(403);
        }

        $exam->delete();
        return back()->with('success', 'Sınav silindi.');
    }
}
