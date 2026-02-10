<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Staff;
use App\Models\Chat;
use App\Models\ClassSection;
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

    public function getClassSections()
    {
        return ClassSection::with(['class', 'section'])->get();
    }

    public function getUsersByRoleAndClass(Request $request)
    {
        $role = $request->role;
        $class_section_id = $request->class_section_id;

        $query = $this->user->builder()
            ->with([
                'roles',
                'guardianRelationChild',
                'class_teacher.teacher',
                'student.class_section.class',
                'student.class_section.section',
                'support_school.school'
            ]);

        // Role filter
        if ($role) {
            $query->whereHas('roles', fn($q) => $q->where('name', $role));
        }

        // 🔹 STUDENT
        if ($role === 'Student' && $class_section_id) {
            $query->whereHas(
                'student.class_section',
                fn($q) =>
                $q->where('id', $class_section_id)
            );
        }

        // 🔹 GUARDIAN (via child)
        if ($role === 'Guardian' && $class_section_id) {
            $query->whereHas(
                'guardianRelationChild.class_section',
                fn($q) =>
                $q->where('id', $class_section_id)
            );
        }

        // 🔹 TEACHER (assigned classes)
        if ($role === 'Teacher' && $class_section_id) {
            $query->whereHas('class_teacher', function ($q) use ($class_section_id) {
                $q->where('class_section_id', $class_section_id);
            });
        }
    // dd($query->get());

        // 🔹 OTHER ROLES → ignore class filter
        $users = $query->get()->map(fn($user) => [
            'id'   => $user->id,
            'name' => $user->full_name ?? trim($user->first_name . ' ' . $user->last_name),
        ]);

        return response()->json($users);
    }
}
