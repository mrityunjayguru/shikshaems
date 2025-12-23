<?php

namespace App\Http\Controllers;

use App\Repositories\Holiday\HolidayInterface;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use App\Services\SessionYearsTrackingsService;
use App\Services\CachingService;
use App\Models\Events;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Throwable;

class EventController extends Controller
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
        $sessionYears = $this->sessionYear->all();
        $current_sessionYear = $this->cache->getDefaultSessionYear();
        $months = sessionYearWiseMonth();
        $schoolSettings = $this->cache->getSchoolSettings();
        return view('events.index', compact('sessionYears', 'months', 'current_sessionYear', 'schoolSettings'));
    }

    public function store(Request $request)
    {
        // ResponseService::noFeatureThenRedirect('Holiday Management');
        // ResponseService::noPermissionThenRedirect('holiday-create');
        $validator = Validator::make($request->all(), [
            'date' => 'required',
            'title' => 'required',
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            $event = new Events();
            $event->title = $request->title;
            $event->desc = $request->description ?? null;
            $event->date = Carbon::createFromFormat('d-m-Y', $request->date)->format('Y-m-d');
            $event->save();

            $sessionYear = $this->cache->getDefaultSessionYear();
            $eventDate = Carbon::parse($request->date);
            $start = Carbon::parse($sessionYear->start_date);
            $end = Carbon::parse($sessionYear->end_date);

            // 🔒 Custom check: date must be within session year
            if ($eventDate->lt($start) || $eventDate->gt($end)) {
                ResponseService::errorResponse('The selected date must fall within the current session year.');
            }

            $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Events', $event->id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Event Controller -> Store Method");
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

        $session_year_id = request('session_year_id');
        $sessionYear = null;

        if (!empty($session_year_id)) {
            $sessionYear = $this->sessionYear->findById((int)$session_year_id);
        }


        $sql = Events::where(function ($query) use ($search) {
            $query->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")->orwhere('title', 'LIKE', "%$search%")->orwhere('desc', 'LIKE', "%$search%")->orwhere('date', 'LIKE', "%$search%");
                });
            });
        })->when($session_year_id, function ($query) use ($sessionYear) {
            $query->whereDate('date', '>=', $sessionYear->start_date)
                ->whereDate('date', '<=', $sessionYear->end_date);
        })->when($month, function ($query) use ($month) {
            $query->whereMonth('date', $month);
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
            $operate = BootstrapTableService::editButton(route('event.update', $row->id));
            $operate .= BootstrapTableService::deleteButton(route('event.destroy', $row->id));
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
        // ResponseService::noFeatureThenRedirect('Holiday Management');
        // ResponseService::noPermissionThenSendJson('holiday-edit');
        $validator = Validator::make($request->all(), ['date' => 'required', 'title' => 'required',]);

        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            $event = Events::where('id', $id)->first();
            // dd($event);
            $event->title = $request->title;
            $event->desc = $request->description ?? null;
            $event->date = Carbon::parse($request->date)->format('Y-m-d');
            $event->save();
            
            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Events', $id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Event Controller -> Update Method");
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        // ResponseService::noFeatureThenRedirect('Holiday Management');
        // ResponseService::noPermissionThenSendJson('holiday-delete');
        try {
            $event = Events::where('id', $id)->delete();
            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->deleteSessionYearsTracking('App\Models\Events', $id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Event Controller -> Delete Method");
            ResponseService::errorResponse();
        }
    }
}
