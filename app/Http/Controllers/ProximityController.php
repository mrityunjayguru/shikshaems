<?php

namespace App\Http\Controllers;

use App\Models\Proximity;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Throwable;

class ProximityController extends Controller
{
    public function index(){
        //  ResponseService::noAnyPermissionThenRedirect(['pickup-points-list', 'pickup-points-create']);
        $proximity = Proximity::first();
        return view('proximity.index', compact('proximity'));
    }

    public function store(Request $request){
        // ResponseService::noPermissionThenSendJson('pickup-points-create');
        $validator = Validator::make($request->all(), [
            'stop_proximity' => 'required',
            'notification_proximity' => 'required'
        ]);

        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }

        try {
            // $proximity = Proximity::first();
            // $proximity->stop_proximity = $request->stop_proximity;
            // $proximity->notification_proximity = $request->notification_proximity;
            // $proximity->save();

            $proximity = Proximity::findOrFail($request->id);
            $proximity->update($request->except('_token'));

            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Proximity Controller -> Store Method");
            ResponseService::errorResponse();
        }        
    }
}
