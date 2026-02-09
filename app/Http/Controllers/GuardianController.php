<?php

namespace App\Http\Controllers;

use App\Repositories\User\UserInterface;
use App\Repositories\ClassSchool\ClassSchoolInterface;
use App\Repositories\Section\SectionInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;
use App\Services\UploadService;
use App\Services\SessionYearsTrackingsService;
// use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Throwable;

class GuardianController extends Controller {
    protected UserInterface $user;
    private ClassSchoolInterface $class;
    private SectionInterface $section;
    private ClassSectionInterface $classSection;
    private SessionYearsTrackingsService $sessionYearsTrackingsService;
    private CachingService $cache;

    public function __construct(UserInterface $user, ClassSchoolInterface $class, SectionInterface $section, ClassSectionInterface $classSection, SessionYearsTrackingsService $sessionYearsTrackingsService, CachingService $cache) {
        $this->user = $user;
        $this->class = $class;
        $this->section = $section;
        $this->classSection = $classSection;
        $this->sessionYearsTrackingsService = $sessionYearsTrackingsService;
        $this->cache = $cache;
    }

    public function index() {
        ResponseService::noPermissionThenRedirect('guardian-list');
        $classes = $this->class->all(['id', 'name', 'medium_id'], ['stream', 'medium']);
        $sections = $this->section->builder()->orderBy('name', 'ASC')->get();

        $class_sections = $this->classSection->all(['*'], ['class', 'class.stream', 'section', 'medium']);
        // $childs = 
        return view('guardian.index', compact('classes','class_sections'));
    }

    public function store(Request $request) {
        ResponseService::noPermissionThenRedirect('guardian-create');
        $request->validate([
            'first_name' => 'required',
            'email'      => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email',
            'last_name'  => 'required',
            'gender'     => 'required',
            'mobile'     => 'required|digits_between:6,15',
        ]);
        try {
            DB::beginTransaction();
            $guardian = $this->user->create($request->all());
            $guardian->assignRole('Guardian');
            $sessionYear = $this->cache->getDefaultSessionYear();
            $semester = $this->cache->getDefaultSemesterData();
            if ($semester) {
                $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Guardian', $guardian->id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, $semester->id);
            } else {
                $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Guardian', $guardian->id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            }
            DB::commit();
            ResponseService::successResponse('Data Created Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Guardian Controller -> Store method");
            ResponseService::errorResponse();
        }
    }

    public function show(Request $request) {
        // dd($request->all());
        ResponseService::noPermissionThenRedirect('guardian-list');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');

        $sql = $this->user->guardian()->with([
                'child.user',         
                'child.class_section',
                'child.class_section.class',
                'child.class_section.section'
            ]);

        if($request->class_id && $request->class_id != 'all')
        {
            $sql->whereHas('child.class_section', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }

        if($request->class_section_id && $request->class_section_id != 'all')
        {
            $sql->whereHas('child', function ($q) use ($request) {
                $q->where('class_section_id', $request->class_section_id);
            });
        }
       
        $sql = $sql->owner();

        if (!empty($_GET['search'])) {
            $search = $_GET['search'];
            $sql->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%$search%")->orwhere('first_name', 'LIKE', "%$search%")
                    ->orwhere('last_name', 'LIKE', "%$search%")->orwhere('gender', 'LIKE', "%$search%")
                    ->orwhere('email', 'LIKE', "%$search%")->orwhere('mobile', 'LIKE', "%$search%");
            });
        }
        $total = $sql->count();

        $sql->orderBy($sort, $order)->skip($offset)->take($limit);
        if ($offset >= $total && $total > 0) {
            $lastPage = floor(($total - 1) / $limit) * $limit; // calculate last page offset
            $offset = $lastPage;
        }
        $res = $sql->get();
        // dd($res);
        $bulkData = array();
        $bulkData['total'] = $total;
        $rows = array();
        $no = 1;
        foreach ($res as $row) {
            $operate = BootstrapTableService::editButton(route('guardian.update', $row->id));
            $wardsOperate = '<a class="btn-view" title="Wards Info" style="padding: 0.65rem 0.5rem;"><svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.5799 11.9999C15.5799 13.9799 13.9799 15.5799 11.9999 15.5799C10.0199 15.5799 8.41992 13.9799 8.41992 11.9999C8.41992 10.0199 10.0199 8.41992 11.9999 8.41992C13.9799 8.41992 15.5799 10.0199 15.5799 11.9999Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.0001 20.27C15.5301 20.27 18.8201 18.19 21.1101 14.59C22.0101 13.18 22.0101 10.81 21.1101 9.39997C18.8201 5.79997 15.5301 3.71997 12.0001 3.71997C8.47009 3.71997 5.18009 5.79997 2.89009 9.39997C1.99009 10.81 1.99009 13.18 2.89009 14.59C5.18009 18.19 8.47009 20.27 12.0001 20.27Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
</a> ';
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['operate'] = $operate;
            $tempRow['wardsOperate'] = $wardsOperate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function update(Request $request) {
        ResponseService::noPermissionThenSendJson('guardian-edit');
        $request->validate([
            'edit_id'    => 'required',
            'first_name' => 'required',
            'email'      => 'nullable|email|max:255|regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/|unique:users,email,' . $request->edit_id,
            'last_name'  => 'required',
            'gender'     => 'required',
            'mobile'     => 'required|digits_between:6,15',
            'image'      => 'nullable|image|mimes:jpeg,png,jpg,svg,gif,webp',
        ]);
        try {
            $data = $request->except('_token', 'edit_id', '_method','reset_password');
            $guardian = $this->user->guardian()->where('id', $request->edit_id)->firstOrFail();
            if (!empty($request->image)) {
                if ($guardian->image) {
                    UploadService::delete($guardian->getRawOriginal('image'));
                }
                $data['image'] = UploadService::upload($request->image, 'guardian');
            }

            if ($request->reset_password) {
                $data['password'] = Hash::make($request->mobile);
            }

            $this->user->guardian()->where('id', $request->edit_id)->update($data);
            $guardian->assignRole('Guardian');
            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e, "Guardian Controller -> Update method");
            ResponseService::errorResponse();
        }
    }

    public function search(Request $request) {
        ResponseService::noAnyPermissionThenSendJson(['student-create', 'student-edit']);
        $parent = $this->user->guardian()->where(function ($query) use ($request) {
            $query->where('email', 'like', '%' . $request->email . '%')
                ->orWhere('first_name', 'like', '%' . $request->email . '%')
                ->orWhere('last_name', 'like', '%' . $request->email . '%');
        })->get();

        if (!empty($parent)) {
            $response = [
                'error' => false,
                'data'  => $parent
            ];
        } else {
            $response = [
                'error'   => true,
                'message' => trans('no_data_found')
            ];
        }
        return response()->json($response);
    }
}
