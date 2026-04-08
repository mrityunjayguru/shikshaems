<?php

namespace App\Http\Controllers;

use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use App\Models\DeviceType;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class DeviceTypeController extends Controller
{
    //

    public function index()
    {
        return view('device-type.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'device_type' => 'required'
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            $gps = new DeviceType();
            $gps->name = $request->name;
            $gps->device_type = $request->device_type;
            $gps->save();

            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "DeviceType Controller -> Store Method");
            ResponseService::errorResponse();
        }
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'edit_name' => 'required',
            'edit_device_type' => 'required'
        ]);
        // dd($request->all());
        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }
        try {
            $gps = DeviceType::where('id', $request->id)->first();
            $gps->name = $request->edit_name;
            $gps->device_type = $request->edit_device_type;
            $gps->save();

            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "DeviceType Controller -> Update Method");
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

        $sql = DeviceType::where(function ($query) use ($search) {
            $query->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")
                        ->orwhere('device_type', 'LIKE', "%$search%")
                        ->orwhere('name', 'LIKE', "%$search%");
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
            $operate = BootstrapTableService::editButton(route('device-type.update', $row->id));
            $operate .= BootstrapTableService::deleteButton(route('device-type.destroy', $row->id));
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['device_type'] = $row->device_type == 0 ? 'Wireless' : 'Wired';
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function destroy(Request $request)
    {
        try {
            DeviceType::where('id', $request->id)->delete();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Device Type Controller -> destroy Method");
            ResponseService::errorResponse();
        }
    }
}
