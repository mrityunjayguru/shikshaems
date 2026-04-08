<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\StudentLeave;
use App\Models\ClassTeacher;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Repositories\User\UserInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;

class LeaveStudentController extends Controller
{
    public function index(){
        return view('student-leave.index');
    }

    public function show(){
        //  ResponseService::noFeatureThenRedirect('Staff Leave Management');
        // ResponseService::noPermissionThenRedirect('approve-leave');

        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $session_year_id = request('session_year_id');
        $filter_upcoming = request('filter_upcoming');
        $month_id = request('month_id');
        $user_id = request('user_id');
        $user = Auth::user();

        $sql = StudentLeave::where(function ($query) use ($search) {
                $query->when($search, function ($query) use ($search) {
                    $query->where(function ($query) use ($search) {
                        $query->where('id', 'LIKE', "%$search%")->orwhere('reason', 'LIKE', "%$search%")->orwhere('from_date', 'LIKE', "%$search%")->orwhere('to_date', 'LIKE', "%$search%")->orwhereHas('user', function ($q) use ($search) {
                            $q->whereRaw('concat(first_name," ",last_name) like ?', "%$search%");
                        });
                    });
                });
            });
        $sql->with('user');

        $user = Auth::user();

        if ($user->hasRole('Teacher')) {

            $classSectionIds = ClassTeacher::where('teacher_id', $user->id)
            ->pluck('class_section_id');

            if ($classSectionIds->isNotEmpty()) {

                $sql->whereHas('student', function ($q) use ($classSectionIds) {
                    $q->whereIn('class_section_id', $classSectionIds);
                });

            } else {
                // Teacher is not a class teacher
                $sql->whereRaw('1 = 0');
            }
        } elseif (!$user->hasRole('School Admin')) {

            // Other roles (optional fallback)
            $sql->whereNot('user_id', $user->id);
        }


        if ($user_id) {
            $sql->where('user_id', $user_id);
        }

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit; // calculate last page offset
            $offset = $lastPage;
        }
        $sql->orderBy('created_at', 'DESC')->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;
        foreach ($res as $row) {
            $operate = '';
            $operate .= BootstrapTableService::editButton(route('student-leave.status.update', $row->id));
            // $operate .= BootstrapTableService::deleteButton(route('student-leave.destroy', $row->id));

            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['days'] = $row->days;
            $tempRow['from_date'] = $row->from_date;
            $tempRow['to_date'] = $row->to_date;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
    
    public function updateStatus(Request $request)
    {
        // ResponseService::noFeatureThenRedirect('Staff Leave Management');
        // ResponseService::noPermissionThenRedirect('approve-leave');

        try {
            DB::beginTransaction();

            $leave = StudentLeave::find($request->id);
            // dd($leave);
            if (!$leave) {
                return ResponseService::errorResponse('Leave record not found');
            }

            $leave->status = $request->status;
            $leave->save();

            $users = [$leave->user_id];
            $type = "Leave";

            DB::commit();

            if ($request->status == 1) {
                send_notification(
                    $users,
                    'Approved',
                    'Your Leave Request Has Been Approved!',
                    $type
                );
            }

            if ($request->status == 2) {
                send_notification(
                    $users,
                    'Rejected',
                    'Your Leave Request Has Been Rejected!',
                    $type
                );
            }

            ResponseService::successResponse('Data Updated Successfully');

        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Leave Controller -> Leave Status Method");
            ResponseService::errorResponse();
        }
    }
}