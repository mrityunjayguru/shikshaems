<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Chat;
use App\Models\User;
use App\Repositories\User\UserInterface;


class ChatHistoryController extends Controller
{
    private UserInterface $user;

    public function __construct(UserInterface $user)
    {
        $this->user = $user;
    }

    public function index()
    {
        // $teachers = Staff::with(['user','class_teacher'])->
        // ->get();

        // dd($teachers);
        return view('chat-history.index');
    }

    public function chatUsers(Request $request)
    {
        $userId = $request->user_id;

        if (!$userId) {
            return response()->json([]);
        }

        $chatUserIds = Chat::where('sender_id', $userId)
            ->orWhere('receiver_id', $userId)
            ->get()
            ->map(function ($chat) use ($userId) {
                return $chat->sender_id == $userId
                    ? $chat->receiver_id
                    : $chat->sender_id;
            })
            ->unique()
            ->values();

        $users = User::whereIn('id', $chatUserIds)
            ->select('id', 'first_name', 'last_name')
            ->get();

        return response()->json($users);
    }

    public function chatMessages(Request $request)
    {
        $request->validate([
            'user_id'      => 'required|integer',
            'chat_user_id' => 'required|integer',
        ]);

        $messages = Chat::where(function ($q) use ($request) {
            $q->where('sender_id', $request->user_id)
                ->where('receiver_id', $request->chat_user_id);
        })
            ->orWhere(function ($q) use ($request) {
                $q->where('sender_id', $request->chat_user_id)
                    ->where('receiver_id', $request->user_id);
            })
            ->with([
                'message.attachment',  
                'message.sender:id,first_name,last_name'
            ])
            ->orderBy('created_at', 'asc')
            ->get();

        return response()->json($messages);
    }


    public function getUsersByRole(Request $request)
    {
        $role = $request->role;

        $query = $this->user->builder()
            ->with('staff', 'roles', 'support_school.school');

        if (!empty($role)) {
            $query->whereHas('roles', function ($q) use ($role) {
                $q->where('name', $role);
            });
        }

        $users = $query->get()->map(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->full_name
                    ?? ($user->first_name . ' ' . $user->last_name),
            ];
        });

        return response()->json($users);
    }
}
