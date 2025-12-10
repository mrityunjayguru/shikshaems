<?php

namespace App\Http\Controllers;

use App\Models\VehicleType;
use App\Models\TransportationPayment;
use Illuminate\Http\Request;
use App\Repositories\Transportation\VehicleRepositoryInterface;
use Carbon\Carbon;
use Throwable;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VehicleTypeController extends Controller
{
    public function index(){
        // ResponseService::noPermissionThenRedirect('schools-list');
        return view('vehicle-type.index');
    }

    public function store(Request $request){
        // ResponseService::noAnyPermissionThenSendJson(['vehicles-create']);

        $validator = Validator::make($request->all(), [
            'vehicle_type' => 'required|string|max:255',
            'vehicle_icon' => 'required',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }

         try {
            if(isset($request->id)){

                $type = new VehicleType();
            }else{

                $type = new VehicleType();
            }
            $type->vehicle_type = $request->vehicle_type;

            if ($request->hasFile('vehicle_icon')) {
                $file = $request->file('vehicle_icon');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('2/vehicle_icons', $filename, 'public'); // storage/app/public/vehicle_icons
                $type->vehicle_icon = $path;
            }

            $type->save();
            // VehicleType::create($request->except('_token'));
            
            ResponseService::successResponse('Vehicle Type created successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "VehicleTypeController -> store");
            ResponseService::errorResponse();
        }
    }

    public function show()
    {
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'desc');
        $search = request('search');
        $showDeleted = request('show_deleted');
        // dd($showDeleted);
        // Base query
        $query = VehicleType::query();

        // If show deleted
        if (!empty($showDeleted)) {
            $query->onlyTrashed();
        }

        // Search
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('vehicle_type', 'LIKE', "%$search%");
            });
        }

        // Total count (before pagination)
        $total = $query->count();

        // Fix offset if goes beyond total
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit;
            $offset = $lastPage;
        }

        // Order + Pagination
        $query->orderBy($sort, $order)->skip($offset)->take($limit);

        // Get records
        $res = $query->get();

        // Prepare response format
        $bulkData = [];
        $bulkData['total'] = $total;

        $rows = [];
        $no = $offset + 1;

        foreach ($res as $row) {

            // Buttons
            if ($showDeleted) {
                $operate = 
                    BootstrapTableService::menuRestoreButton('restore', route('vehicle-type.restore', $row->id)) .
                    BootstrapTableService::menuTrashButton('delete', route('vehicle-type.trash', $row->id));
            } else {
                $operate = 
                    BootstrapTableService::menuEditButton('edit', route('vehicle-type.update', $row->id)) .
                    BootstrapTableService::menuDeleteButton('delete', route('vehicle-type.destroy', $row->id));
            }

            // Convert model to array
            $tempRow = $row->toArray();

            $tempRow['no'] = $no++;
            $tempRow['status'] = $row->status ? __('active') : __('inactive');
            $tempRow['operate'] = BootstrapTableService::menuItem($operate);

            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;

        return response()->json($bulkData);
    }

    public function update(Request $request, $id){
         try {
            $type = VehicleType::where('id', $id)->first();
            $type->vehicle_type = $request->vehicle_type;

            if ($request->hasFile('vehicle_icon')) {
                $file = $request->file('vehicle_icon');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('2/vehicle_icons', $filename, 'public'); // storage/app/public/2/vehicle_icons
                $type->vehicle_icon = $path;
            }

            $type->save();
            // VehicleType::create($request->except('_token'));
            
            ResponseService::successResponse('Vehicle Type Updated successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "VehicleTypeController -> store");
            ResponseService::errorResponse();
        }
    }

     public function destroy($id)
    {
        // ResponseService::noPermissionThenSendJson('vehicles-delete');

        try {
            $vehicle = VehicleType::where('id', $id)->first();
            $vehicle->delete();

            ResponseService::successResponse('Vehicle Type deleted successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "VehicleTypeController -> destroy method");
            ResponseService::errorResponse();
        }
    }
}
