<?php

use App\Http\Controllers\Api\ApiController;
use App\Http\Controllers\Api\ParentApiController;
use App\Http\Controllers\Api\StaffApiController;
use App\Http\Controllers\Api\StudentApiController;
use App\Http\Controllers\Api\TeacherApiController;
use App\Http\Controllers\Api\TrasportationApiController;
use App\Http\Controllers\SubscriptionWebhookController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

/**
 * Webhook Routes
 **/
Route::post('subscription/webhook/stripe', [SubscriptionWebhookController::class, 'stripe']);
Route::post('subscription/webhook/razorpay', [SubscriptionWebhookController::class, 'razorpay']);

// Route::group(['middleware' => 'auth:sanctum'], static function () {
//     Route::post('logout', [ApiController::class, 'logout']);
// });

Route::group(['middleware' => 'APISwitchDatabase'], static function () {
    Route::post('logout', [ApiController::class, 'logout']);
});
Route::get('fees-due-notification', [ApiController::class, 'sendFeeNotification']);
/**
 * STUDENT APIs
 **/
Route::get('school-details', [ApiController::class, 'schoolDetails']);
Route::group(['prefix' => 'student'], static function () {

    //Non Authenticated APIs
    Route::post('login', [StudentApiController::class, 'login']);
    Route::post('forgot-password', [StudentApiController::class, 'forgotPassword']);

    //Authenticated APIs
    Route::group(['middleware' => ['APISwitchDatabase', 'checkSchoolStatus']], static function () {
        Route::get('class-subjects', [StudentApiController::class, 'classSubjects']);
        Route::get('subjects', [StudentApiController::class, 'subjects']);
        Route::post('select-subjects', [StudentApiController::class, 'selectSubjects']);
        Route::get('guradian-details', [StudentApiController::class, 'getGuardianDetails']);
        Route::get('timetable', [StudentApiController::class, 'getTimetable']);
        Route::get('lessons', [StudentApiController::class, 'getLessons']);
        Route::get('lesson-topics', [StudentApiController::class, 'getLessonTopics']);
        Route::get('assignments', [StudentApiController::class, 'getAssignments']);
        Route::post('submit-assignment', [StudentApiController::class, 'submitAssignment']);
        Route::post('delete-assignment-submission', [StudentApiController::class, 'deleteAssignmentSubmission']);
        Route::get('attendance', [StudentApiController::class, 'getAttendance']);
        Route::get('announcements', [StudentApiController::class, 'getAnnouncements']);
        Route::get('get-exam-list', [StudentApiController::class, 'getExamList']); // Exam list Route
        Route::get('get-exam-details', [StudentApiController::class, 'getExamDetails']); // Exam Details Route
        Route::get('exam-marks', [StudentApiController::class, 'getExamMarks']); // Exam Details Route
        Route::get('sliders', [StudentApiController::class, 'getSliders']); // Sliders

        // online exam routes
        Route::get('get-online-exam-list', [StudentApiController::class, 'getOnlineExamList']); // Get Online Exam List Route
        Route::get('get-online-exam-questions', [StudentApiController::class, 'getOnlineExamQuestions']); // Get Online Exam Questions Route
        Route::post('submit-online-exam-answers', [StudentApiController::class, 'submitOnlineExamAnswers']); // Submit Online Exam Answers Details Route
        Route::get('get-online-exam-result-list', [StudentApiController::class, 'getOnlineExamResultList']); // Online exam result list Route
        Route::get('get-online-exam-result', [StudentApiController::class, 'getOnlineExamResult']); // Online exam result  Route

        //reports
        Route::get('get-online-exam-report', [StudentApiController::class, 'getOnlineExamReport']); // Online Exam Report Route
        Route::get('get-assignments-report', [StudentApiController::class, 'getAssignmentReport']); // Assignment Report Route

        // profile data
        Route::get('get-profile-data', [StudentApiController::class, 'getProfileDetails']); // Get Profile Data

        // Session Year
        Route::get('current-session-year', [StudentApiController::class, 'getSessionYear']);

        Route::get('school-settings', [StudentApiController::class, 'getSchoolSettings']);

        //student leave-request
        Route::post('leave-request', [StudentApiController::class, 'leaveRequest']);

        // student diaries
        // Route::get('/diaries', [StudentApiController::class, 'getStudentDiaries']);
        Route::get('/diary-details', [StudentApiController::class, 'showStudentDiaryDetail']);
    });
});

/**
 * PARENT APIs
 **/
Route::group(['prefix' => 'parent'], static function () {
    //Non Authenticated APIs
    // Route::group(['middleware' => ['APISwitchDatabase']], static function () {
    Route::post('login', [ParentApiController::class, 'login']);
    //Authenticated APIs
    // Route::group(['middleware' => ['']], static function () {
    //     Route::get('test', [ParentApiController::class, 'test']);
    // });
    Route::group(['middleware' => ['APISwitchDatabase']], static function () {
        Route::get('test', [ParentApiController::class, 'test']);

        Route::group(['middleware' => ['checkChild', 'APISwitchDatabase']], static function () {
            Route::get('subjects', [ParentApiController::class, 'subjects']);
            Route::get('class-subjects', [ParentApiController::class, 'classSubjects']);
            Route::get('timetable', [ParentApiController::class, 'getTimetable']);
            Route::get('lessons', [ParentApiController::class, 'getLessons']);
            Route::get('lesson-topics', [ParentApiController::class, 'getLessonTopics']);
            Route::get('assignments', [ParentApiController::class, 'getAssignments']);
            Route::get('attendance', [ParentApiController::class, 'getAttendance']);
            Route::get('teachers', [ParentApiController::class, 'getTeachers']);
            Route::get('sliders', [ParentApiController::class, 'getSliders']); // Sliders

            // Offline Exams
            Route::get('get-exam-list', [ParentApiController::class, 'getExamList']); // Exam list Route
            Route::get('get-exam-details', [ParentApiController::class, 'getExamDetails']); // Exam Details Route
            Route::get('exam-marks', [ParentApiController::class, 'getExamMarks']); //Exam Marks

            // Fees
            Route::group(['prefix' => 'fees'], static function () {
                Route::get('/', [ParentApiController::class, 'getFees']);
                Route::post('/compulsory/pay', [ParentApiController::class, 'payCompulsoryFees']);
                Route::post('/optional/pay', [ParentApiController::class, 'payOptionalFees']);
                Route::get('/receipt', [ParentApiController::class, 'feesPaidReceiptPDF']); //Fees Receipt
            });

            // Online Exam
            Route::get('get-online-exam-list', [ParentApiController::class, 'getOnlineExamList']); // Get Online Exam List Route
            Route::get('get-online-exam-result-list', [ParentApiController::class, 'getOnlineExamResultList']); // Online exam result list Route
            Route::get('get-online-exam-result', [ParentApiController::class, 'getOnlineExamResult']); // Online exam result  Route

            // Reports
            Route::get('get-online-exam-report', [ParentApiController::class, 'getOnlineExamReport']); // Online Exam Report Route
            Route::get('get-assignments-report', [ParentApiController::class, 'getAssignmentReport']); // Assignment Report Route

            // Session Year
            Route::get('current-session-year', [ParentApiController::class, 'getSessionYear']);
            Route::get('school-settings', [ParentApiController::class, 'getSchoolSettings']);

            // profile data
            Route::get('get-child-profile-data', [ParentApiController::class, 'getChildProfileDetails']); // Get Profile Data

            // Announcements
            Route::get('announcements', [ParentApiController::class, 'getAnnouncements']);

            // student diaries
            //Route::get('/diaries', [ParentApiController::class, 'getStudentDiaries']);
            Route::get('/diary-details', [ParentApiController::class, 'showStudentDiaryDetail']);
            
            //class and subject teachers
            Route::post('teachers-list', [TeacherApiController::class, 'getTeachersForChild']);

            //
            Route::post('apply-leave', [ParentApiController::class, 'applyLeave']);
            Route::get('get-leaves', [ParentApiController::class, 'getLeaves']);
            Route::post('submit-support', [ParentApiController::class, 'support']);
        });
    });
    // });
});

/**
 * TEACHER APIs
 **/
Route::group(['prefix' => 'teacher'], static function () {
    //Non Authenticated APIs
    Route::post('login', [TeacherApiController::class, 'login']);
    //Authenticated APIs
    Route::group(['middleware' => ['APISwitchDatabase', 'checkSchoolStatus']], static function () {

        Route::get('subjects', [TeacherApiController::class, 'subjects']);

        //Assignment
        Route::get('get-assignment', [TeacherApiController::class, 'getAssignment']);
        Route::post('create-assignment', [TeacherApiController::class, 'createAssignment']);
        Route::post('update-assignment', [TeacherApiController::class, 'updateAssignment']);
        Route::post('delete-assignment', [TeacherApiController::class, 'deleteAssignment']);

        //Assignment Submission
        Route::get('get-assignment-submission', [TeacherApiController::class, 'getAssignmentSubmission']);
        Route::post('update-assignment-submission', [TeacherApiController::class, 'updateAssignmentSubmission']);

        //File
        Route::post('delete-file', [TeacherApiController::class, 'deleteFile']);
        Route::post('update-file', [TeacherApiController::class, 'updateFile']);

        //Lesson
        Route::get('get-lesson', [TeacherApiController::class, 'getLesson']);
        Route::post('create-lesson', [TeacherApiController::class, 'createLesson']);
        Route::post('update-lesson', [TeacherApiController::class, 'updateLesson']);
        Route::post('delete-lesson', [TeacherApiController::class, 'deleteLesson']);

        //Topic
        Route::get('get-topic', [TeacherApiController::class, 'getTopic']);
        Route::post('create-topic', [TeacherApiController::class, 'createTopic']);
        Route::post('update-topic', [TeacherApiController::class, 'updateTopic']);
        Route::post('delete-topic', [TeacherApiController::class, 'deleteTopic']);

        //Announcement
        Route::get('get-announcement', [TeacherApiController::class, 'getAnnouncement']);
        Route::post('send-announcement', [TeacherApiController::class, 'sendAnnouncement']);
        Route::post('update-announcement', [TeacherApiController::class, 'updateAnnouncement']);
        Route::post('delete-announcement', [TeacherApiController::class, 'deleteAnnouncement']);

        Route::get('get-attendance', [TeacherApiController::class, 'getAttendance']);
        Route::post('submit-attendance', [TeacherApiController::class, 'submitAttendance']);


        //Exam
        Route::get('get-exam-list', [TeacherApiController::class, 'getExamList']); // Exam list Route
        Route::get('get-exam-details', [TeacherApiController::class, 'getExamDetails']); // Exam Details Route
        Route::post('submit-exam-marks/subject', [TeacherApiController::class, 'submitExamMarksBySubjects']); // Submit Exam Marks By Subjects Route
        Route::post('submit-exam-marks/student', [TeacherApiController::class, 'submitExamMarksByStudent']); // Submit Exam Marks By Students Route

        Route::group(['middleware' => ['auth:sanctum', 'checkStudent']], static function () {
            Route::get('get-student-result', [TeacherApiController::class, 'GetStudentExamResult']); // Student Exam Result
            Route::get('get-student-marks', [TeacherApiController::class, 'GetStudentExamMarks']); // Student Exam Marks
        });

        //Student List
        Route::get('student-list', [TeacherApiController::class, 'getStudentList']);
        Route::get('student-details', [TeacherApiController::class, 'getStudentDetails']);

        //Schedule List
        Route::get('teacher_timetable', [TeacherApiController::class, 'getTeacherTimetable']);

        Route::post('class-detail', [TeacherApiController::class, 'getClassDetail']);

        // student diaries categories
        Route::get('/diary-categories', [TeacherApiController::class, 'getStudentDiaryCategories']);
        Route::post('/create-diary-category', [TeacherApiController::class, 'createStudentDiaryCategory']);
        Route::post('/update-diary-category', [TeacherApiController::class, 'updateStudentDiaryCategory']);
        Route::post('/delete-diary-category', [TeacherApiController::class, 'deleteStudentDiaryCategory']);
        Route::post('/restore-diary-category', [TeacherApiController::class, 'restoreStudentDiaryCategory']);
        Route::post('/trash-diary-category', [TeacherApiController::class, 'trashStudentDiaryCategory']);

        // student diaries
        //Route::get('/diaries', [TeacherApiController::class, 'getStudentDiaries']);
        Route::post('/create-diary', [TeacherApiController::class, 'createStudentDiary']);
        Route::post('/delete-diary', [TeacherApiController::class, 'deleteStudentDiary']);
        Route::post('/remove-student', [TeacherApiController::class, 'removeStudent']);
    });
});


// Staff & Teacher APIs
Route::group(['prefix' => 'staff'], static function () {
    Route::post('login', [TeacherApiController::class, 'login']);

    Route::group(['middleware' => ['APISwitchDatabase', 'checkSchoolStatus']], static function () {
        // Payroll
        Route::get('my-payroll', [StaffApiController::class, 'myPayroll']);
        Route::get('payroll-slip', [StaffApiController::class, 'myPayrollSlip']);
        Route::post('payroll-create', [StaffApiController::class, 'storePayroll']);
        Route::get('payroll-staff-list', [StaffApiController::class, 'staffPayrollList']);

        Route::get('payroll-year', [StaffApiController::class, 'payrollYear']);


        Route::get('profile', [StaffApiController::class, 'profile']);
        Route::get('counter', [StaffApiController::class, 'counter']);
        Route::get('teachers', [StaffApiController::class, 'teacher']);
        Route::get('teacher-timetable', [StaffApiController::class, 'teacherTimetable']);
        Route::get('staffs', [StaffApiController::class, 'staff']);

        // Attendance
        Route::get('attendance', [StaffApiController::class, 'getAttendance']);

        Route::get('leave-request', [StaffApiController::class, 'leaveRequest']);
        Route::post('leave-approve', [StaffApiController::class, 'leaveApprove']);
        Route::post('leave-delete', [StaffApiController::class, 'leaveDelete']);

        // Announcement
        Route::get('get-announcement', [StaffApiController::class, 'getAnnouncement']);
        Route::post('send-announcement', [StaffApiController::class, 'sendAnnouncement']);
        Route::post('update-announcement', [StaffApiController::class, 'updateAnnouncement']);
        Route::post('delete-announcement', [StaffApiController::class, 'deleteAnnouncement']);

        Route::get('student/attendance', [StaffApiController::class, 'studentAttendance']);

        Route::get('roles', [StaffApiController::class, 'getRoles']);
        Route::get('users', [StaffApiController::class, 'getUsers']);
        Route::post('notification', [StaffApiController::class, 'storeNotification']);
        Route::get('notification', [StaffApiController::class, 'getNotification']);
        Route::post('notification-delete', [StaffApiController::class, 'deleteNotification']);

        Route::get('get-fees', [StaffApiController::class, 'getFees']);
        Route::get('fees-paid-list', [StaffApiController::class, 'getFeesPaidList']);

        Route::get('student-offline-exam-result', [StaffApiController::class, 'getOfflineExamResult']);
        Route::get('features-permission', [StaffApiController::class, 'getFeaturesPermissions']);

        Route::get('class-timetable', [StaffApiController::class, 'getClassTimetable']);

        Route::get('student-fees-receipt', [StaffApiController::class, 'feesReceipt']);
        Route::get('allowances-deductions', [StaffApiController::class, 'allowancesDeductions']);
    });
});

/**
 * GENERAL APIs
 **/
Route::get('settings', [ApiController::class, 'getSettings']);
Route::post('forgot-password', [ApiController::class, 'forgotPassword']);


// Route::group(['middleware' => ['auth:sanctum',]], static function () {
Route::group(['middleware' => ['APISwitchDatabase',]], static function () {
    Route::get('school-settings', [ApiController::class, 'getSchoolSettings']);
    Route::get('holidays', [ApiController::class, 'getHolidays']);
    Route::get('events', [ApiController::class, 'getEvents']);
    Route::get('birthdays', [ApiController::class, 'getBirthdays']);
    Route::post('change-password', [ApiController::class, 'changePassword']);
    //    Route::get('test', [ApiController::class, 'getPaymentMethod']);
    Route::get('payment-confirmation', [ApiController::class, 'getPaymentConfirmation'])->name('payment-confirmation');
    Route::get('payment-transactions', [ApiController::class, 'getPaymentTransactions'])->name('payment-transactions');
    Route::get('gallery', [ApiController::class, 'getGallery']);
    Route::get('session-years', [ApiController::class, 'getSessionYear']);
    //    Route::get('features', [ApiController::class, 'getFeatures']);

    // Leaves
    Route::get('leaves', [ApiController::class, 'getLeaves']);
    Route::post('leaves', [ApiController::class, 'applyLeaves']);
    Route::get('my-leaves', [ApiController::class, 'getMyLeaves']);
    Route::post('delete-my-leaves', [ApiController::class, 'deleteLeaves']);
    Route::get('staff-leaves-details', [ApiController::class, 'getStaffLeaveDetail']);
    Route::get('leave-settings', [ApiController::class, 'leaveSettings']);

    Route::get('medium', [ApiController::class, 'getMedium']);
    Route::get('classes', [ApiController::class, 'getClass']);

    Route::post('update-profile', [ApiController::class, 'updateProfile']);
    Route::get('student-exan-result-pdf', [ApiController::class, 'getExamResultPdf']);

    Route::post('message', [ApiController::class, 'sendMessage']);
    Route::get('message', [ApiController::class, 'getMessage']);
    Route::post('delete/message', [ApiController::class, 'deleteMessage']);
    Route::post('message/read', [ApiController::class, 'readMessage']);

    // Get users from role
    // Student, Teacher, Guardian, Other Staff [Teachers / School Staff]
    // Get all users
    Route::get('users', [ApiController::class, 'getUsers']);

    // Get history
    Route::get('users/chat/history', [ApiController::class, 'usersChatHistory']);

    Route::post('class-section/teachers', [ApiController::class, 'classSectionTeachers']);

    Route::get('student-details', [ApiController::class, 'getStudentDetails']);

    Route::get('pickup-points', [TrasportationApiController::class, 'pickupPoints']);

    Route::get('transportation-fees', [TrasportationApiController::class, 'transportation_fees']);

    Route::get('transportation-shifts', [TrasportationApiController::class, 'transportation_shifts']);

    Route::post('transportation/live-route', [TrasportationApiController::class, 'pickupPointsTrack']);

    Route::post('transport/dashboard', [TrasportationApiController::class, 'getTransportationData']);

    Route::post('transport/plans/current', [TrasportationApiController::class, 'getTransoprtationCurrentPlan']);

    Route::post('transport/routes/stops', [TrasportationApiController::class, 'getTransoprtationRouteForUser']);

    Route::post('transport/attendance/user-list', [TrasportationApiController::class, 'getTransoprtationAttendanceUsers']);

    Route::post('transport/attendance/create', [TrasportationApiController::class, 'getTransoprtationAttendanceStore']);

    Route::post('transport/requests', [TrasportationApiController::class, 'getTransportationRequests']);

    Route::post('transportation-requests', [TrasportationApiController::class, 'transportation_requests']);

    Route::post('transportation-payments', [TrasportationApiController::class, 'transportation_payments']);

    Route::post('create-transportation-expense', [TrasportationApiController::class, 'transportation_expense_create']);

    Route::get('get-transportation-expense', [TrasportationApiController::class, 'transportation_expense_get']);

    Route::get('transport/expense/categories/list', [TrasportationApiController::class, 'getTransportationExpenseCategory']);

    Route::get('driver-helpr/dashboard', [TrasportationApiController::class, 'getDriverHelperDashboard']);

    Route::get('driver-helpr/get-vehicle-details', [TrasportationApiController::class, 'getVehicleDetails']);

    Route::get('driver-helpr/get-trips', [TrasportationApiController::class, 'getDriverHelperTrips']);

    Route::post('driver-helpr/trip/start-end', [TrasportationApiController::class, 'tripStartEnd']);

    Route::post('get-vehicle-assignment-status', [TrasportationApiController::class, 'getvehicleAssignmentstatus']);

    Route::post('transport/user/attendance-list', [TrasportationApiController::class, 'getTransportationAteendaceRecordForUser']);

    Route::get('diaries', [ApiController::class, 'getStudentDiaries']);

    Route::get('syllabus', [ApiController::class, 'getSyllabus']);

    Route::post('track-vehicles', [TrasportationApiController::class, 'trackVehicles']);
    
    // Live Trip Tracking APIs
    Route::get('trip/live-tracking', [TrasportationApiController::class, 'getLiveTracking']);
    Route::get('trip/stops', [TrasportationApiController::class, 'getTripStops']);
    
    // My Wards Transportation API
    Route::get('my-wards', [TrasportationApiController::class, 'getMyWards']);
});

// Get all cached trips (for debugging)
Route::get('test-cached-trips', function() {
    try {
        // Try to get all trip cache keys
        $trips = [];
        
        // Check for trips 1-50 (adjust range as needed)
        for ($i = 1; $i <= 50; $i++) {
            $tripCache = \Illuminate\Support\Facades\Cache::get("trip_{$i}");
            if ($tripCache) {
                $trips[] = [
                    'trip_id' => $i,
                    'has_data' => true,
                    'current_location' => $tripCache['current_location'] ?? null,
                    'next_stop' => $tripCache['next_stop']['name'] ?? 'N/A'
                ];
            }
        }
        
        return response()->json([
            'success' => true,
            'message' => 'Found ' . count($trips) . ' cached trips',
            'trips' => $trips
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Get all schools (for debugging)
Route::get('test-schools', function() {
    try {
        $schools = \App\Models\School::select('id', 'name', 'database_name', 'traccar_phone')
            ->where('status', 1)
            ->get();
        
        return response()->json([
            'success' => true,
            'schools' => $schools,
            'total' => $schools->count()
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'success' => false,
            'message' => 'Error: ' . $e->getMessage()
        ], 500);
    }
});

// Test Broadcast Endpoint (for testing Pusher) - Fetches live data from API
Route::match(['GET', 'POST'], 'test-broadcast/{tripId}/{schoolId?}', function(\Illuminate\Http\Request $request, $tripId, $schoolId = null) {
    try {
        // Get school_id from URL parameter, request body, or query string
        $schoolId = $schoolId ?? $request->input('school_id') ?? $request->query('school_id');
        
        if (!$schoolId) {
            return response()->json([
                'success' => false,
                'message' => 'school_id is required. Use: /api/test-broadcast/{tripId}/{schoolId} or pass as parameter',
                'example' => '/api/test-broadcast/19/7'
            ], 400);
        }

        // Get school details from main database
        $school = \App\Models\School::find($schoolId);   
        if (!$school) {
            return response()->json([
                'success' => false,
                'message' => 'School not found with ID: ' . $schoolId
            ], 404);
        }

        // Switch to school database
        $schoolDbName = $school->database_name;
        if (!$schoolDbName) {
            return response()->json([
                'success' => false,
                'message' => 'School database name not configured'
            ], 404);
        }

        // Set school database connection
        config(['database.connections.school.database' => $schoolDbName]);
        \Illuminate\Support\Facades\DB::purge('school');
        \Illuminate\Support\Facades\DB::reconnect('school');

        // Get trip details from school database
        $trip = \App\Models\RouteVehicleHistory::on('school')->with([
            'route.routePickupPoints.pickupPoint',
            'vehicle'
        ])->find($tripId);

        if (!$trip) {
            return response()->json([
                'success' => false,
                'message' => 'Trip not found with ID: ' . $tripId . ' in school database: ' . $schoolDbName
            ], 404);
        }

        if (!$trip->vehicle) {
            return response()->json([
                'success' => false,
                'message' => 'Vehicle not found for this trip'
            ], 404);
        }

        // Get GPS device from main database using vehicle's gps_id
        $gps = \App\Models\GPS::where('assigned_to', $trip->vehicle->id)->first();
        
        if (!$gps || !$gps->imei_no) {
            return response()->json([
                'success' => false,
                'message' => 'GPS device not found for vehicle ID: ' . $trip->vehicle->id
            ], 404);
        }

        // Get Traccar phone from school
        $traccarPhone = $school->traccar_phone ?? env('TRACCAR_PHONE');
        
        if (!$traccarPhone) {
            return response()->json([
                'success' => false,
                'message' => 'School Traccar phone not configured'
            ], 404);
        }

        // Get Traccar URL from env (same as TraccarHttpListener uses)
        $traccarBaseUrl = rtrim(str_replace('wss://', 'https://', env('TRACCAR_SOCKET_URL', 'https://trackback.trackroutepro.com')), '/api/socket');
        $traccarAuthUrl = env('TRACCAR_AUTH_URL', 'https://app.trackroutepro.com/Auth/verifyUser');

        // Authenticate with Traccar
        $authResponse = \Illuminate\Support\Facades\Http::asForm()->post($traccarAuthUrl, [
            'phone' => env('TRACCAR_PHONE')
        ]);

        if (!$authResponse->successful()) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to authenticate with Traccar'
            ], 500);
        }

        $sessionId = $authResponse->json()['jsessionid'] ?? null;
        
        if (!$sessionId) {
            return response()->json([
                'success' => false,
                'message' => 'No session ID received from Traccar'
            ], 500);
        }

        // Get device ID from Traccar using IMEI
        $devicesUrl = "{$traccarBaseUrl}/api/devices";
        
        $devicesCh = curl_init($devicesUrl);
        curl_setopt($devicesCh, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($devicesCh, CURLOPT_HTTPHEADER, [
            'Cookie: JSESSIONID=' . $sessionId,
            'Content-Type: application/json'
        ]);
        curl_setopt($devicesCh, CURLOPT_SSL_VERIFYPEER, false);
        
        $devicesResponse = curl_exec($devicesCh);
        $httpCode = curl_getinfo($devicesCh, CURLINFO_HTTP_CODE);
        curl_close($devicesCh);
        
        if ($httpCode !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch devices from Traccar. HTTP Code: ' . $httpCode,
                'response' => $devicesResponse
            ], 500);
        }
        
        $devices = json_decode($devicesResponse, true);
        
        if (!$devices || !is_array($devices)) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid devices response from Traccar',
                'raw_response' => $devicesResponse,
                'decoded' => $devices
            ], 500);
        }
        
        $traccarDevice = null;
        
        // Debug: collect all device info
        $availableDevices = [];
        foreach ($devices as $device) {
            $deviceImei = $device['uniqueId'] ?? '';
            $deviceName = $device['name'] ?? '';
            $deviceId = $device['id'] ?? '';
            
            $availableDevices[] = [
                'id' => $deviceId,
                'name' => $deviceName,
                'uniqueId' => $deviceImei
            ];
            
            // Try exact match and trimmed match on IMEI
            if ($deviceImei && ($deviceImei === $gps->imei_no || trim($deviceImei) === trim($gps->imei_no))) {
                $traccarDevice = $device;
                break;
            }
            
            // Also try matching by name if IMEI is empty
            if (!$deviceImei && $deviceName && strpos($deviceName, $gps->imei_no) !== false) {
                $traccarDevice = $device;
                break;
            }
        }
        
        if (!$traccarDevice) {
            return response()->json([
                'success' => false,
                'message' => 'Traccar device not found with IMEI: ' . $gps->imei_no,
                'looking_for' => $gps->imei_no,
                'available_devices' => $availableDevices,
                'total_devices' => count($devices),
                'hint' => 'Device uniqueId is empty in Traccar. Please set the IMEI in Traccar device settings.'
            ], 404);
        }

        // Fetch live position from Traccar API
        $traccarDeviceId = $traccarDevice['id'];
        $apiUrl = "{$traccarBaseUrl}/api/positions?deviceId={$traccarDeviceId}";

        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Cookie: JSESSIONID=' . $sessionId,
            'Content-Type: application/json'
        ]);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        
        $response = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);

        if ($httpCode !== 200) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to fetch position from Traccar. HTTP Code: ' . $httpCode,
                'traccar_url' => $apiUrl
            ], 500);
        }

        $positions = json_decode($response, true);
        
        if (empty($positions)) {
            return response()->json([
                'success' => false,
                'message' => 'No position data available from Traccar'
            ], 404);
        }

        $position = $positions[0];

        // Extract position data
        $latitude = $position['latitude'];
        $longitude = $position['longitude'];
        $speed = round(($position['speed'] ?? 0) * 1.852, 2); // knots to km/h
        $deviceTime = \Carbon\Carbon::parse($position['deviceTime'])->format('Y-m-d H:i:s');
        $attributes = $position['attributes'] ?? [];

        // Use SimpleTripTrackingService to process and broadcast (includes Pusher)
        $trackingService = app(\App\Services\SimpleTripTrackingService::class);
        $trackingService->processGPSData($tripId, $latitude, $longitude, $speed, $deviceTime, $attributes);

        // Get the cached data that was just updated
        $trackingData = \Illuminate\Support\Facades\Cache::get("trip_{$tripId}");

        return response()->json([
            'success' => true,
            'message' => 'Live data fetched and broadcasted successfully',
            'data' => $trackingData,
            'source' => 'live_api',
            'school_db' => $schoolDbName,
            'position' => [
                'latitude' => $latitude,
                'longitude' => $longitude,
                'speed' => $speed,
                'device_time' => $deviceTime
            ]
        ]);

    } catch (\Exception $e) {
        \Illuminate\Support\Facades\Log::error('Test broadcast error: ' . $e->getMessage());
        return response()->json([
            'success' => false,
            'message' => 'Broadcast failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});
