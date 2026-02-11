<?php

namespace App\Http\Controllers;

use App\Models\DeviceType;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use App\Models\GPS;
use App\Models\School;
use App\Models\Vehicle;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Throwable;


class GpsController extends Controller
{
    //

    public function index()
    {
        $deviceType = DeviceType::select('id', 'name')->get();
        return view('gps.index', compact('deviceType'));
    }

    public function schoolGpsIndex()
    {
        return view('gps.school-gps');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'device_type_id' => 'required',
            'imei_no' => 'required',
            'sim_no' => 'required',
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            $gps = new GPS();
            $gps->device_type_id = $request->device_type_id;
            $gps->imei_no = $request->imei_no;
            $gps->sim_no = $request->sim_no;
            $gps->save();

            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "GPS Controller -> Store Method");
            ResponseService::errorResponse();
        }
    }

    public function update(Request $request)
    {
        // dd($request->all());
        try {
            $gps = GPS::where('id', $request->id)->first();
            $gps->device_type_id = $request->edit_device_type_id;
            $gps->imei_no = $request->edit_imei_no;
            $gps->sim_no = $request->edit_sim_no;
            $gps->save();

            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "GPS Controller -> Update Method");
            ResponseService::errorResponse();
        }
    }

    public function show()
    {
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');

        $sql = GPS::with('device_type', 'school')
            ->when($search, function ($query) use ($search) {

                $query->where(function ($query) use ($search) {

                    $query->where('id', 'LIKE', "%$search%")
                        ->orWhere('imei_no', 'LIKE', "%$search%")
                        ->orWhere('sim_no', 'LIKE', "%$search%")

                        ->orWhereHas('school', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%");
                        })
                        ->orWhereHas('device_type', function ($q) use ($search) {
                            $q->where('name', 'LIKE', "%$search%");
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
            $vehicle = '';
            if ($row->school_id) {
                $school = School::where('id', $row->school_id)->first();

                // Set database name dynamically
                Config::set('database.connections.school.database', $school->database_name);

                // Clear previous connection cache
                DB::purge('school');
                DB::reconnect('school');

                $vehicle = DB::connection('school')
                    ->table('vehicles')
                    ->where('id', $row->assigned_to)
                    ->first();
            }

            $operate = BootstrapTableService::editButton(route('gps.update', $row->id));
            $operate .= BootstrapTableService::deleteButton(route('gps.destroy', $row->id));
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['status'] = $row->status == 0 ? 'Unassigned' : 'Assigned';
            $tempRow['created_at'] = format_date($row->created_at);
            $tempRow['assigned_at'] = $row->assigned_at ? format_date($row->assigned_at) : '-';
            $tempRow['vehicle_number'] = $vehicle ? $vehicle->vehicle_number : '-';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function schoolGpsShow()
    {
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');

        DB::beginTransaction();
        $sql = GPS::with('device_type', 'school')->where(function ($query) use ($search) {
            $query->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")
                        ->orwhere('name', 'LIKE', "%$search%")
                        ->orwhere('imei_no', 'LIKE', "%$search%")
                        ->orwhere('sim_no', 'LIKE', "%$search%")
                        ->orwhere('wired_device', 'LIKE', "%$search%");
                });
            });
        });

        $sql->where('school_id', Auth::user()->school_id);
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
            $vehicle = Vehicle::where('id', $row->assigned_to)->first();

            $operate = BootstrapTableService::editButton(route('gps.update', $row->id));
            $operate .= BootstrapTableService::deleteButton(route('gps.destroy', $row->id));
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['status'] = $row->status == 0 ? 'Unassigned' : 'Assigned';
            $tempRow['gps_status'] = $row->gps_status == 0 ? 'Inactive' : 'Active';
            $tempRow['type'] = $row->device_type->device_type == 0 ? 'Wireless' : 'Wired';
            $tempRow['created_at'] = format_date($row->created_at);
            $tempRow['assigned_on'] = $row->assigned_on ? format_date($row->assigned_on) : '-';
            $tempRow['vehicle_number'] = $vehicle->vehicle_number ?? '-';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        DB::commit();
        return response()->json($bulkData);
    }

    public function destroy(Request $request)
    {
        try {
            GPS::where('id', $request->id)->delete();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "GPS Controller -> destroy Method");
            ResponseService::errorResponse();
        }
    }
}
