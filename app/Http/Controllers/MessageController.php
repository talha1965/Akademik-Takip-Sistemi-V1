<?php

namespace App\Http\Controllers;

use App\Models\Message;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MessageController extends Controller
{
    // Gelen Kutusunu Göster
    public function index()
    {
        $user = Auth::user();
        
        // Bana gelen mesajlar (En yeniden eskiye)
        $receivedMessages = Message::where('receiver_id', $user->id)
                                   ->with('sender')
                                   ->orderBy('created_at', 'desc')
                                   ->get();

        // Gönderdiğim mesajlar
        $sentMessages = Message::where('sender_id', $user->id)
                               ->with('receiver')
                               ->orderBy('created_at', 'desc')
                               ->get();

        // Mesaj gönderebileceğim kişiler (Sisteme kayıtlı diğer kullanıcılar/hocalar)
        // Gerçek bir sistemde sadece hocaları getirmek için where('role', 'teacher') eklenebilir.
        $users = User::where('id', '!=', $user->id)->get();

        return view('messages.index', compact('receivedMessages', 'sentMessages', 'users'));
    }

    // Yeni Mesaj Gönder
    public function store(Request $request)
    {
        $request->validate([
            'receiver_id' => 'required|exists:users,id',
            'subject' => 'nullable|string|max:255',
            'body' => 'required|string',
        ]);

        Message::create([
            'sender_id' => Auth::id(),
            'receiver_id' => $request->receiver_id,
            'subject' => $request->subject ?? 'Konusuz',
            'body' => $request->body,
        ]);

        return back()->with('success', 'Mesajınız başarıyla gönderildi!');
    }

    // Mesajı Okundu İşaretle
    public function markAsRead($id)
    {
        $message = Message::where('receiver_id', Auth::id())->findOrFail($id);
        $message->update(['is_read' => true]);
        
        return response()->json(['success' => true]);
    }
}