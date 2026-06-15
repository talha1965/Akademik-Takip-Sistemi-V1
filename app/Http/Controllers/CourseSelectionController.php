namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use Illuminate\Support\Facades\Auth;

class CourseSelectionController extends Controller
{
    // Ders Seçim Ekranını Göster
    public function index()
    {
        $user = Auth::user();
        
        // Öğrencinin mevcut seçtiği dersler
        $myCourses = $user->courses;
        $totalAkts = $myCourses->sum('akts');

        // Havuzdaki tüm dersler (Öğrencinin halihazırda aldıklarını havuzda "Seçildi" olarak göstermek için)
        $allCourses = Course::all();

        return view('course-selection', compact('allCourses', 'myCourses', 'totalAkts'));
    }

    // Ders Ekleme İşlemi
    public function enroll(Request $request, $courseId)
    {
        $user = Auth::user();
        $course = Course::findOrFail($courseId);

        // 1. Kontrol: Öğrenci bu derse zaten kayıtlı mı?
        if ($user->courses()->where('course_id', $courseId)->exists()) {
            return back()->with('error', 'Bu derse zaten kayıtlısınız.');
        }

        // 2. Kontrol: Kontenjan dolu mu?
        if ($course->available_quota <= 0) {
            return back()->with('error', 'Bu dersin kontenjanı dolmuştur.');
        }

        // 3. Kontrol: Maksimum AKTS sınırı (Örn: 30 AKTS) eklenebilir.
        $totalAkts = $user->courses->sum('akts');
        if (($totalAkts + $course->akts) > 30) {
            return back()->with('error', 'Dönemlik maksimum 30 AKTS alabilirsiniz.');
        }

        // Derse kayıt yap
        $user->courses()->attach($courseId);

        return back()->with('success', 'Ders başarıyla eklendi.');
    }

    // Dersi Bırakma İşlemi
    public function drop(Request $request, $courseId)
    {
        $user = Auth::user();
        $user->courses()->detach($courseId);

        return back()->with('success', 'Ders başarıyla bırakıldı.');
    }
}