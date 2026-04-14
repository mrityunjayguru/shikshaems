<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TransportationPayment;
use App\Models\Vehicle;
use App\Models\Shift;
use App\Models\PickupPoint;
use App\Models\RouteVehicle;
use App\Models\TransportationFee;
use App\Models\TransportationRequest;
use App\Models\PaymentTransaction;
use App\Repositories\User\UserInterface;
use App\Services\ResponseService;
use App\Services\BootstrapTableService;
use Throwable;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Auth;
use App\Services\CachingService;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Repositories\ClassSection\ClassSectionInterface;

class TransportationRequestController extends Controller
{
    private UserInterface $user;
    private CachingService $cache;
    private ClassSectionInterface $classSection;
    public function __construct(UserInterface $user, CachingService $cache, ClassSectionInterface $classSection, )
    {
        $this->user = $user;
        $this->cache = $cache;
        $this->classSection = $classSection;
    }
    public function index()
    {
        ResponseService::noAnyPermissionThenSendJson(['transportationRequests-list']);
        $transportationRequests = TransportationPayment::with(['user', 'pickupPoint', 'shift'])
            ->where('status', 'paid')
            ->get();

        $pickupPoints  = \App\Models\PickupPoint::where('status', 1)->get(['id', 'name']);
        $routeVehicles = \App\Models\RouteVehicle::with(['vehicle:id,name', 'route:id,name'])->get();
        $shifts        = \App\Models\Shift::where('status', 1)->get(['id', 'name']);

        return view('transportation-request.index', compact('transportationRequests', 'pickupPoints', 'routeVehicles', 'shifts'));
    }

    public function show()
    {
        ResponseService::noPermissionThenRedirect('transportationRequests-list');
        $today          = now();
        $offset         = request('offset', 0);
        $limit          = request('limit', 10);
        $sort           = request('sort', 'id');
        $order          = request('order', 'desc');
        $search         = request('search');
        $pickupPointId  = request('pickup_point_id');
        $shiftId        = request('shift_id');
        $routeVehicleId = request('route_vehicle_id');
        $paymentStatus  = request('payment_status'); // paid | unpaid | partial

        // -----------------------------------------------
        // Unified: TransportationRequest (all requests)
        // with payment status from TransportationPayment
        // -----------------------------------------------
        $sql = TransportationRequest::with([
            'user',
            'pickupPoint',
            'transportationFee',
            'routeVehicle.vehicle',
            'routeVehicle.route',
            'shift',
        ])
        ->when(!empty($pickupPointId),  fn($q) => $q->where('pickup_point_id', $pickupPointId))
        ->when(!empty($routeVehicleId), fn($q) => $q->where('route_vehicle_id', $routeVehicleId))
        ->when(!empty($shiftId),        fn($q) => $q->where('shift_id', $shiftId));

        // Payment status filter
        if ($paymentStatus === 'paid') {
            $paidUserIds = TransportationPayment::where('status', 'paid')->pluck('user_id');
            $sql->whereIn('user_id', $paidUserIds);
        } elseif ($paymentStatus === 'partial') {
            $partialUserIds = TransportationPayment::where('status', 'partial')->pluck('user_id');
            $paidUserIds    = TransportationPayment::where('status', 'paid')->pluck('user_id');
            $sql->whereIn('user_id', $partialUserIds)->whereNotIn('user_id', $paidUserIds);
        } elseif ($paymentStatus === 'unpaid') {
            $anyPaidUserIds = TransportationPayment::whereIn('status', ['paid', 'partial'])->pluck('user_id');
            $sql->whereNotIn('user_id', $anyPaidUserIds);
        }

        if (!empty($search)) {
            $sql->where(function ($q) use ($search) {
                $q->whereHas('user', function ($d) use ($search) {
                    $d->where('first_name', 'LIKE', "%$search%")
                      ->orWhere('last_name', 'LIKE', "%$search%")
                      ->orWhereRaw("CONCAT(first_name,' ',last_name) LIKE ?", ["%{$search}%"]);
                })->orWhereHas('pickupPoint', fn($d) => $d->where('name', 'LIKE', "%$search%"));
            });
        }

        $total = $sql->count();
        if ($offset >= $total && $total > 0) {
            $offset = floor(($total - 1) / $limit) * $limit;
        }
        $res = $sql->orderBy($sort, $order)->skip($offset)->take($limit)->get();

        $rows = [];
        $no   = $offset + 1;

        foreach ($res as $row) {
            // Get payment info for this user
            $payment = TransportationPayment::where('user_id', $row->user_id)
                ->whereIn('status', ['paid', 'partial'])
                ->orderBy('id', 'desc')
                ->first();

            $feeAmount   = (float) ($row->transportationFee->fee_amount ?? 0);
            $paidAmount  = TransportationPayment::where('user_id', $row->user_id)
                ->whereIn('status', ['paid', 'partial'])
                ->sum('amount');
            $dueAmount   = max(0, $feeAmount - $paidAmount);

            if ($paidAmount >= $feeAmount && $feeAmount > 0) {
                $payStatus = 'Paid';
            } elseif ($paidAmount > 0) {
                $payStatus = 'Partial (₹' . number_format($paidAmount, 0) . ' / ₹' . number_format($feeAmount, 0) . ')';
            } else {
                $payStatus = 'Unpaid';
            }

            $role = 'Student';
            if ($row->user) {
                if ($row->user->hasRole('Teacher'))      $role = 'Teacher';
                elseif (!$row->user->hasRole('Student')) $role = 'Staff';
            }

            // Action buttons
            $operate = BootstrapTableService::editButton(route('transportation-requests.update', $row->id));
            // if ($payment && $row->user->hasrole('Student')) {
            //     $operate .= BootstrapTableService::button(
            //         '<svg width="24" height="24" viewBox="0 0 24 24" fill="none"><path d="M12 2V8L14 6" stroke="white" stroke-width="1.5"/><path d="M12 8L10 6" stroke="white" stroke-width="1.5"/><path d="M7 12C3 12 3 13.79 3 16V17C3 19.76 3 22 8 22H16C20 22 21 19.76 21 17V16C21 13.79 21 12 17 12" stroke="white" stroke-width="1.5"/></svg>',
            //         route('transportation-requests.fee-receipt', $payment->id),
            //         ['btn', 'btn-xs', 'btn-gradient-info', 'btn-rounded', 'btn-icon'],
            //         ['target' => '_blank', 'title' => trans('generate_pdf') . ' ' . trans('fees')]
            //     );
            // }

            $tempRow                   = $row->toArray();
            $tempRow['no']             = $no++;
            $tempRow['role']           = $role;
            $tempRow['payment_status'] = $payStatus;
            $tempRow['paid_amount']    = $paidAmount;
            $tempRow['due_amount']     = $dueAmount;
            $tempRow['fee_amount']     = $feeAmount;
            $tempRow['operate']        = $operate;
            $rows[] = $tempRow;
        }

        return response()->json(['total' => $total, 'rows' => $rows]);
    }

    public function update(Request $request, $id)
    {
        ResponseService::noPermissionThenSendJson(['transportationRequests-edit']);

        $validator = Validator::make($request->all(), [
            'edit_route_id' => 'required|numeric|exists:route_vehicles,id',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            $requestData = [
                'route_vehicle_id' => $request->edit_route_id
            ];

            $transportationPayment = TransportationPayment::find($id);

            $sessionYear = $this->cache->getDefaultSessionYear();
            $today = now();
            $existingPayment = TransportationPayment::where('user_id', $transportationPayment->user_id)
                ->whereNotNull('route_vehicle_id')
                ->where('session_year_id', $sessionYear->id)
                ->where('expiry_date', '>', $today)
                ->where('status', 'paid')
                ->first();

            if ($existingPayment) {
                ResponseService::errorResponse('This user already has an active paid record in the current session year.');
            }

            $routes = RouteVehicle::with('vehicle')->where('id', $request->edit_route_id)->first();

            $assignedCounts = TransportationPayment::selectRaw('route_vehicle_id, COUNT(*) as assigned_students')
                ->where('route_vehicle_id', $request->edit_route_id)
                ->where('status', 'paid')
                ->groupBy('route_vehicle_id')->first();

            if (!empty($routes) && !empty($assignedCounts) && ((int) $routes->vehicle->capacity - (int) $assignedCounts->assigned_students) == 0) {
                ResponseService::errorResponse("No seats left in this vehicle");
            }

            if (!$transportationPayment) {
                return redirect()->back()->with('error', 'Transportation payment not found.');
            }

            // Update attributes
            $transportationPayment->update($requestData);

            DB::commit();
            ResponseService::successResponse('Data updated successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> update');
            ResponseService::errorResponse();
        }
    }

    public function cancelTransportationService($id)
    {
        ResponseService::noPermissionThenSendJson(['transportationRequests-edit']);

        try {
            DB::beginTransaction();
            $today = now();
            $transportationPayment = TransportationPayment::find($id);

            if (!$transportationPayment) {
                return redirect()->back()->with('error', 'Transportation payment not found.');
            }


            // Update attributes
            $transportationPayment->update(['expiry_date' => $today->format('Y-m-d')]);

            DB::commit();
            ResponseService::successResponse('Service cancelled successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> cancelTransportationService');
            ResponseService::errorResponse();
        }
    }

    public function getVehicleRoutes($pickupPointId)
    {
        ResponseService::noPermissionThenSendJson(['transportationRequests-list']);
        $validator = Validator::make(['pickup_point_id' => $pickupPointId], [
            'pickup_point_id' => 'required|numeric|exists:pickup_points,id',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }

        try {
            $today = now();
            $routes = RouteVehicle::with('vehicle', 'route.shift')
                ->whereHas('route.routePickupPoints', function ($query) use ($pickupPointId) {
                    $query->where('pickup_point_id', $pickupPointId);
                });
            $routes = $routes->get();

            $assignedCounts = TransportationPayment::selectRaw('route_vehicle_id, COUNT(*) as assigned_students')
                ->whereNotNull('route_vehicle_id')
                ->where('status', 'paid')
                ->where('expiry_date', '>', $today)
                ->groupBy('route_vehicle_id')
                ->pluck('assigned_students', 'route_vehicle_id');

            $fees = TransportationFee::where('pickup_point_id', $pickupPointId)->get();

            return response()->json([
                'success' => true,
                'data' => $routes,
                'assignedCounts' => $assignedCounts,
                'fees' => $fees,
            ]);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> getVehicleRoutes');
            return ResponseService::errorResponse();
        }
    }

    public function changeStatusBulk(Request $request)
    {
        ResponseService::noPermissionThenRedirect('transportationRequests-edit');
        $validator = Validator::make($request->all(), [
            'vehicle_route' => 'required|numeric|exists:route_vehicles,id',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }
        try {
            DB::beginTransaction();

            $paymentIds = json_decode($request->ids, true); // decode to array

            TransportationPayment::whereIn('id', $paymentIds)
                ->update(['route_vehicle_id' => $request->vehicle_route]);

            DB::commit();

            ResponseService::successResponse("Status Updated Successfully");
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function offlineEntry()
    {
        ResponseService::noAnyPermissionThenRedirect(['transportationRequests-create']);

        $class_sections = $this->classSection->all(['*'], ['class', 'class.stream', 'section', 'medium']);
        $pickupPoints = pickupPoint::where('status', 1)->get();
        $shifts = Shift::where('status', 1)->get();


        return view('transportation-request.offline_entry', compact('pickupPoints', 'class_sections', 'shifts'));

    }

    public function getStudents($id)
    {
        ResponseService::noPermissionThenRedirect('transportationRequests-create');
        try {
            $students = $this->user->builder()
                ->role('Student')
                ->select('id', 'first_name', 'last_name')
                ->with([
                    'student' => function ($query) {
                        $query->select('id', 'class_section_id', 'user_id', 'guardian_id')
                            ->with([
                                'class_section' => function ($query) {
                                    $query->select('id', 'class_id', 'section_id', 'medium_id')
                                        ->with('class:id,name', 'section:id,name', 'medium:id,name');
                                }
                            ]);
                    }
                ])
                ->whereHas('student', function ($q) use ($id) {
                    $q->where('class_section_id', $id);
                })
                ->get();

            return response()->json([
                'success' => true,
                'data' => $students,
            ]);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> getStudents');
            return ResponseService::errorResponse();
        }

    }
    public function getTeachers()
    {
        ResponseService::noPermissionThenRedirect('transportationRequests-create');
        try {
            $teachers = $this->user->builder()->role('Teacher')->select('*')->get();

            return response()->json([
                'success' => true,
                'data' => $teachers,
            ]);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> getTeacher');
            return ResponseService::errorResponse();
        }

    }
    public function getStaff()
    {
        ResponseService::noPermissionThenRedirect('transportationRequests-create');
        try {
            $staff = $this->user->builder()->select('id', 'first_name', 'last_name', 'image')->has('staff')->with('roles', 'support_school.school:id,name')->whereHas('roles', function ($q) {
                $q->where('custom_role', 1)->whereNot('name', 'Teacher');
            })->get();

            return response()->json([
                'success' => true,
                'data' => $staff,
            ]);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> getTeacher');
            return ResponseService::errorResponse();
        }

    }

    public function offlineEntryStore(Request $request)
    {
        ResponseService::noPermissionThenRedirect('transportationRequests-create');

        $validator = Validator::make($request->all(), [
            'user_id'          => 'required|numeric|exists:users,id',
            'pickup_point_id'  => 'required|numeric|exists:pickup_points,id',
            'route_vehicle_id' => 'required|numeric|exists:route_vehicles,id',
            'fee_id'           => 'nullable|numeric|exists:transportation_fees,id',
        ], [
            'user_id.required'          => 'Please select a user.',
            'user_id.exists'            => 'Selected user does not exist.',
            'pickup_point_id.required'  => 'Please select a pickup point.',
            'pickup_point_id.exists'    => 'Selected pickup point does not exist.',
            'route_vehicle_id.required' => 'Please select a vehicle route.',
            'route_vehicle_id.exists'   => 'Selected route vehicle does not exist.',
            'fee_id.exists'             => 'Selected fee does not exist.',
        ]);

        if ($validator->fails()) {
            ResponseService::validationError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $sessionYear = $this->cache->getDefaultSessionYear();

            // Check if request already exists for this user in current session year
            $existing = TransportationRequest::where('user_id', $request->user_id)
                ->where('session_year_id', $sessionYear->id)
                ->first();

            if ($existing) {
                ResponseService::errorResponse('Transportation request already exists for this user in current session year.');
            }

            // Get shift_id from route vehicle's route
            $routeVehicle = RouteVehicle::with('route')->find($request->route_vehicle_id);
            $shiftId = $routeVehicle?->route?->shift_id;

            TransportationRequest::create([
                'user_id'               => $request->user_id,
                'pickup_point_id'       => $request->pickup_point_id,
                'route_vehicle_id'      => $request->route_vehicle_id,
                'shift_id'              => $shiftId,
                'transportation_fee_id' => $request->fee_id ?? null,
                'status'                => 0,
                'session_year_id'       => $sessionYear->id,
            ]);

            DB::commit();
            ResponseService::successResponse('Transportation request created successfully.');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> offlineEntryStore');
            ResponseService::errorResponse();
        }
    }

    public function feeReceipt($id)
    {

        ResponseService::noAnyPermissionThenRedirect(['transportationRequests-receipt']);

        try {

            $TransportationPayment = TransportationPayment::where('status', 'paid')->with('pickupPoint', 'transportationFee', 'paymentTransaction')->where('id', $id)->first();

            $student = $this->user->builder()->role('Student')->select('id', 'first_name', 'last_name')
                ->with([
                    'student' => function ($query) {
                        $query->select('id', 'class_section_id', 'user_id', 'guardian_id', 'admission_no')->with([
                            'class_section' => function ($query) {
                                $query->select('id', 'class_id', 'section_id', 'medium_id')->with('class:id,name', 'section:id,name', 'medium:id,name');
                            }
                        ]);
                    }
                ])->where('id', $TransportationPayment->user_id)->first();

            $school = $this->cache->getSchoolSettings();

            $data = explode("storage/", $school['horizontal_logo'] ?? '');
            $school['horizontal_logo'] = end($data);

            if ($school['horizontal_logo'] == null) {
                $systemSettings = $this->cache->getSystemSettings();
                $data = explode("storage/", $systemSettings['horizontal_logo'] ?? '');
                $school['horizontal_logo'] = end($data);
            }

            $pdf = Pdf::loadView('transportation-request.fee_receipt', compact('school', 'TransportationPayment', 'student'));
            return $pdf->stream('transportation-fees-receipt.pdf');

        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, 'TransportationRequestController -> feeReceipt');
            ResponseService::errorResponse();
        }
    }
}
