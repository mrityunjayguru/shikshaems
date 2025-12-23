<?php

namespace App\Http\Controllers;

use App\Repositories\Holiday\HolidayInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use App\Services\SessionYearsTrackingsService;
use App\Services\CachingService;
use App\Models\Code;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Throwable;

class CodeController extends Controller
{
    private HolidayInterface $holiday;
    private SessionYearInterface $sessionYear;
    private SessionYearsTrackingsService $sessionYearsTrackingsService;
    private CachingService $cache;

    public function __construct(HolidayInterface $holiday, SessionYearInterface $sessionYear, SessionYearsTrackingsService $sessionYearsTrackingsService, CachingService $cache)
    {
        $this->holiday = $holiday;
        $this->sessionYear = $sessionYear;
        $this->sessionYearsTrackingsService = $sessionYearsTrackingsService;
        $this->cache = $cache;
    }

    public function index()
    {
        return view('code.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code' => 'required',
            'end_date' => 'required',
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }

        try {
            $code = new Code();
            $code->code = $request->code;
            $code->end_date = $request->end_date;
            $code->save();

            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Code Controller -> Store Method");
            ResponseService::errorResponse();
        }
    }

    public function show(Request $request)
    {
        // ResponseService::noFeatureThenRedirect('Holiday Management');
        // ResponseService::noPermissionThenRedirect('holiday-list');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        // $session_year_id = request('session_year_id');
        $month = request('month');

        $sql = Code::where(function ($query) use ($search) {
            $query->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")->orwhere('code', 'LIKE', "%$search%")->orwhere('end_date', 'LIKE', "%$search%");
                });
            });
        });

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit; // calculate last page offset
            $offset = $lastPage;
        }
        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        $res = $sql->get();

        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;
        foreach ($res as $row) {
            $operate = BootstrapTableService::editButton(route('code.update', $row->id));
            $operate .= BootstrapTableService::deleteButton(route('code.destroy', $row->id));
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            // $tempRow['date'] = format_date($row->date);
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function update($id, Request $request)
    {
        // dd($request->all());
        $validator = Validator::make($request->all(), ['code' => 'required', 'end_date' => 'required',]);

        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            $code = Code::where('id', $id)->first();
            // dd($code);
            $code->code = $request->code;
            $code->end_date = $request->end_date;
            $code->save();

            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Code Controller -> Update Method");
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        try {
            Code::where('id', $id)->delete();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Code Controller -> Delete Method");
            ResponseService::errorResponse();
        }
    }

    public function codeValidate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'code'    => 'required',
        ]);
        if ($validator->fails()) {
            $this->ajaxValidationError($validator->errors(), trans('common.error'));
        }

        try {
            // Validate Ticket
            $data = Code::where(['status' => '1'])->where('code', $request->code)->first();
            // dd($data);
            if (empty($data)) {
                ResponseService::errorResponse('Invalid Tracking code');
            }

            if (date("Y-m-d") > $data->end_date) {
                ResponseService::errorResponse('Code has been expired');
            }

             ResponseService::successResponse('Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Code Controller -> code validate Method");
            ResponseService::errorResponse();
        }
    }
}
