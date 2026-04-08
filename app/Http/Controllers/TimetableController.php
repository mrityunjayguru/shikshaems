<?php

namespace App\Http\Controllers;

use App\Models\Timetable;
use App\Repositories\ClassSchool\ClassSchoolInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Repositories\Medium\MediumInterface;
use App\Repositories\SchoolSetting\SchoolSettingInterface;
use App\Repositories\Subject\SubjectInterface;
use App\Repositories\SubjectTeacher\SubjectTeacherInterface;
use App\Repositories\Timetable\TimetableInterface;
use App\Repositories\User\UserInterface;
use App\Services\BootstrapTableService;
use App\Services\SessionYearsTrackingsService;
use App\Services\CachingService;
use App\Services\ResponseService;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;

class TimetableController extends Controller
{
    private SubjectTeacherInterface $subjectTeacher;
    private SubjectInterface $subject;
    private TimetableInterface $timetable;
    private ClassSectionInterface $classSection;
    private UserInterface $user;
    private SchoolSettingInterface $schoolSettings;
    private CachingService $cache;
    private ClassSchoolInterface $class;
    private MediumInterface $medium;
    private SessionYearsTrackingsService $sessionYearsTrackingsService;

    public function __construct(SubjectTeacherInterface $subjectTeacher, SubjectInterface $subject, TimetableInterface $timetable, ClassSectionInterface $classSection, UserInterface $user, SchoolSettingInterface $schoolSettings, CachingService $cache, ClassSchoolInterface $class, MediumInterface $medium, SessionYearsTrackingsService $sessionYearsTrackingsService)
    {
        $this->subjectTeacher = $subjectTeacher;
        $this->subject = $subject;
        $this->timetable = $timetable;
        $this->classSection = $classSection;
        $this->user = $user;
        $this->schoolSettings = $schoolSettings;
        $this->cache = $cache;
        $this->class = $class;
        $this->medium = $medium;
        $this->sessionYearsTrackingsService = $sessionYearsTrackingsService;
    }

    public function index()
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect('timetable-list');

        // Get Timetable Settings Data
        $timetableData = $this->schoolSettings->getBulkData([
            'timetable_start_time',
            'timetable_end_time',
            'timetable_duration'
        ]);

        // Convert Timetable Duration time to number
        $timetableData['timetable_duration'] = Carbon::parse($timetableData['timetable_duration'] ?? "00:00:00")->diffInMinutes(Carbon::parse('00:00:00'));

        $classes = $this->class->builder()->with('stream')->get()->pluck('full_name', 'id');
        $mediums = $this->medium->builder()->pluck('name', 'id');


        return view('timetable.index', compact('timetableData', 'classes', 'mediums'));
    }

    public function store(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect(['timetable-create']);
        $request->validate([
            'subject_teacher_id' => 'nullable|numeric',
            'class_section_id' => 'required|numeric',
            'subject_id' => 'nullable|numeric',
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'required',
            'note' => 'nullable',
        ]);
        try {
            // Check for teacher conflicts
            if ($request->subject_teacher_id) {
                // First get the teacher_id from the subject_teacher record
                $subjectTeacher = $this->subjectTeacher->findById($request->subject_teacher_id);
                if ($subjectTeacher) {
                    $teacher_id = $subjectTeacher->teacher_id;
                    
                    // Now check for conflicts using the teacher_id
                    $conflictingTimetable = $this->timetable->builder()
                        ->where('day', $request->day)
                        ->whereHas('subject_teacher', function($q) use ($teacher_id) {
                            $q->where('teacher_id', $teacher_id);
                        })
                        ->where(function($query) use ($request) {
                            $query->where(function($q) use ($request) {
                                $q->where('start_time', '<=', $request->start_time)
                                  ->where('end_time', '>', $request->start_time);
                            })->orWhere(function($q) use ($request) {
                                $q->where('start_time', '<', $request->end_time)
                                  ->where('end_time', '>=', $request->end_time);
                            });
                        })
                        ->first();

                    if ($conflictingTimetable) {
                        ResponseService::errorResponse('Teacher is already scheduled for another class at this time.');
                    }
                }
            }
            $timetable = $this->timetable->create([
                ...$request->all(),
                'type' => (!empty($request->subject_id)) ? "Lecture" : "Break"
            ]);
            $sessionYear = $this->cache->getDefaultSessionYear();
            $semester = $this->cache->getDefaultSemesterData();
            if ($semester) {
                $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Timetable', $timetable->id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, $semester->id);
            } else {
                $this->sessionYearsTrackingsService->storeSessionYearsTracking('App\Models\Timetable', $timetable->id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            }
            ResponseService::successResponse('Data Stored Successfully', $timetable);
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function edit($classSectionID)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect('timetable-edit');
        $currentSemester = $this->cache->getDefaultSemesterData();
        $classSection = $this->classSection->findById($classSectionID, ['*'], ['class', 'class.stream', 'section', 'medium']);

        $subjectTeachers = $this->subjectTeacher->builder()
            ->with([
                'subject:id,name,type,bg_color',
                'teacher:id,first_name,last_name',
                'class_subject'
            ])
            ->whereHas('class_section', function ($q) use ($classSectionID) {
                $q->where('id', $classSectionID);
            })
            ->whereHas('class_subject', function ($q) {
                $q->whereNull('deleted_at');
            })
            ->orderBy('subject_id', 'ASC')
            ->CurrentSemesterData()
            ->get();

        $subjectWithoutTeacherAssigned = $this->subject->builder()
            ->with([
                'class_subjects' => function ($query) use ($classSection) {
                    $query->where('class_id', $classSection->class_id)
                        ->CurrentSemesterData();
                }
            ])
            ->whereHas('class_subjects', function ($q) use ($classSection) {
                $q->where('class_id', $classSection->class_id)
                    ->CurrentSemesterData();
            })
            ->select(['id', 'name', 'type', 'bg_color'])
            ->whereNotIn('id', $subjectTeachers->pluck('subject_id'))
            ->get();

        $timetables = $this->timetable->builder()
            ->where('class_section_id', $classSectionID)
            ->with([
                'teacher:users.id,first_name,last_name',
                'subject:id,name,type,bg_color',
                'subject.class_subjects',
                'subject_teacher.class_subject'
            ])
            ->CurrentSemesterData()
            ->get();

        // Get Timetable Settings Data
        $timetableSettingsData = $this->schoolSettings->getBulkData([
            'timetable_start_time',
            'timetable_end_time',
            'timetable_duration'
        ]);
        return view('timetable.edit', compact('subjectTeachers', 'subjectWithoutTeacherAssigned', 'classSection', 'timetables', 'timetableSettingsData', 'currentSemester'));
    }

    public function update(Request $request, $id)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect(['timetable-edit']);
        $request->validate([
            'start_time' => 'required',
            'end_time' => 'required',
            'day' => 'required',
        ]);
        $start_time = $request->start_time;
        $end_time = $request->end_time;
        $start_time = Carbon::createFromFormat('H:i:s', $start_time)->format('H:i:s');
        $end_time = Carbon::createFromFormat('H:i:s', $end_time)->format('H:i:s');

        $schoolSettings = $this->cache->getSchoolSettings();
        $timetable_start_time = Carbon::createFromFormat('H:i:s', $schoolSettings['timetable_start_time'])->format('H:i:s');
        $timetable_end_time = Carbon::createFromFormat('H:i:s', $schoolSettings['timetable_end_time'])->format('H:i:s');

        try {
            if ($timetable_start_time <= $start_time && $timetable_end_time >= $end_time) {
                $this->timetable->updateOrCreate(['id' => $id,], $request->all());
                ResponseService::successResponse('Data Stored Successfully');
            } else {
                ResponseService::errorResponse('Please select a valid time');
            }
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }


    public function show(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect('timetable-list');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');

        $schoolSettings = $this->cache->getSchoolSettings([
            'timetable_start_time',
            'timetable_end_time',
            'timetable_duration'
        ]);

        if ($schoolSettings->timetable_start_time ?? '' && $schoolSettings->timetable_end_time ?? '') {
            $sql = $this->classSection->builder()->with([
                'class:id,name,stream_id',
                'class.stream',
                'section:id,name',
                'medium:id,name',
                'timetable' => function ($query) use ($schoolSettings) {
                    $query->CurrentSemesterData()
                        ->where('start_time', '>=', $schoolSettings->timetable_start_time)->where('end_time', '<=', $schoolSettings->timetable_end_time)->with('subject:id,name,type');
                }
            ]);
        } else {
            $sql = $this->classSection->builder()->with([
                'class:id,name,stream_id',
                'class.stream',
                'section:id,name',
                'medium:id,name',
                'timetable' => function ($query) {
                    $query->CurrentSemesterData()->with('subject:id,name,type');
                }
            ]);
        }


        if (!empty($request->search)) {
            $search = $request->search;
            $sql->where(function ($query) use ($search) {
                $query->orWhereHas('section', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('medium', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('class', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                })->orWhereHas('class.stream', function ($q) use ($search) {
                    $q->where('name', 'LIKE', "%$search%");
                });
            });
        }
        if (!empty($request->medium_id)) {
            $sql = $sql->where('medium_id', $request->medium_id);
        }

        if (!empty($request->class_id)) {
            $sql = $sql->where('class_id', $request->class_id);
        }

        if (!empty($request->section_id)) {
            $sql = $sql->where('section_id', $request->section_id);
        }

        if (!empty($request->medium_id)) {
            $sql = $sql->where('medium_id', $request->medium_id);
        }

        if (!empty($request->teacher_id)) {
            $sql = $sql->whereHas('class_teachers', function ($q) use ($request) {
                $q->where('teacher_id', $request->teacher_id);
            });
        }

        if (!empty($request->subject_id)) {
            $sql = $sql->whereHas('subject_teachers', function ($q) use ($request) {
                $q->where('subject_id', $request->subject_id);
            });
        }

        if (!empty($request->class_subject_id)) {
            $sql = $sql->whereHas('subject_teachers.class_subject', function ($q) use ($request) {
                $q->where('id', $request->class_subject_id);
            });
        }

        if (!empty($showDeleted)) {
            $sql = $sql->onlyTrashed();
        }

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
            $operate = BootstrapTableService::editButton(route('timetable.edit', $row->id), false);
            $operate .= BootstrapTableService::button('<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M7.81958 2.5H16.1897C17.9103 2.5 19.2241 3.01093 20.1067 3.89355C20.9892 4.77611 21.5002 6.08929 21.5002 7.80957V16.1797C21.5002 17.9003 20.9893 19.2141 20.1067 20.0967C19.2241 20.9793 17.9103 21.4902 16.1897 21.4902H7.81958C6.09931 21.4901 4.78612 20.9792 3.90356 20.0967C3.02094 19.2141 2.51001 17.9003 2.51001 16.1797V7.80957C2.5101 6.08922 3.02101 4.77611 3.90356 3.89355C4.78612 3.011 6.09923 2.50009 7.81958 2.5Z" stroke="white"/>
<path d="M16.86 8.46008C16.02 8.38008 15.25 8.33008 14.5 8.28008L14.42 7.80008C14.35 7.32008 14.2 6.33008 12.69 6.33008H11.3C9.81004 6.33008 9.65004 7.28008 9.57004 7.79008L9.49004 8.26007C9.06004 8.29007 8.64004 8.31007 8.21004 8.35007L7.12003 8.46008C6.74003 8.50008 6.47003 8.83008 6.51003 9.21008C6.55003 9.56008 6.84004 9.83008 7.19004 9.83008C7.21004 9.83008 7.24003 9.83008 7.26003 9.83008L8.34003 9.72008C8.94003 9.67008 9.55003 9.62008 10.15 9.59008C11.37 9.54008 12.59 9.56008 13.82 9.62008C14.73 9.66008 15.68 9.73008 16.72 9.83008C16.74 9.83008 16.76 9.83008 16.78 9.83008C17.13 9.83008 17.43 9.56008 17.46 9.21008C17.51 8.83008 17.24 8.49008 16.86 8.46008Z" fill="white"/>
<path d="M15.83 11.1001C15.66 10.9101 15.41 10.8101 15.16 10.8101H8.84C8.59 10.8101 8.34 10.9201 8.17 11.1001C8 11.2901 7.91 11.5401 7.93 11.7901L8.24001 15.7501C8.30001 16.6001 8.37 17.6601 10.29 17.6601H13.71C15.63 17.6601 15.7 16.6001 15.76 15.7501L16.07 11.7901C16.09 11.5401 16 11.2901 15.83 11.1001Z" fill="white"/>
</svg>
', '#', ['delete-class-timetable', 'btn-gradient-dark'], ['title' => trans("Delete Class Timetable"), 'data-id' => $row->id]);
            $tempRow = $row->toArray();
            $timetable = $row->timetable->groupBy('day')->sortBy('start_time');
            $tempRow['no'] = $no++;
            $tempRow['Monday'] = $timetable['Monday'] ?? [];
            $tempRow['Tuesday'] = $timetable['Tuesday'] ?? [];
            $tempRow['Wednesday'] = $timetable['Wednesday'] ?? [];
            $tempRow['Thursday'] = $timetable['Thursday'] ?? [];
            $tempRow['Friday'] = $timetable['Friday'] ?? [];
            $tempRow['Saturday'] = $timetable['Saturday'] ?? [];
            $tempRow['Sunday'] = $timetable['Sunday'] ?? [];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function destroy($id)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenSendJson('timetable-delete');
        try {
            Timetable::find($id)->delete();
            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->deleteSessionYearsTracking('App\Models\Timetable', $id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function teacherIndex()
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect('timetable-list');

        // Get Timetable Settings Data
        $timetableSettingsData = $this->schoolSettings->getBulkData([
            'timetable_start_time',
            'timetable_end_time',
            'timetable_duration'
        ]);
        return view('timetable.teacher.index', compact('timetableSettingsData'));
    }

    public function teacherList(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect('timetable-list');
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $sql = $this->user->builder()->role('Teacher')->with([
            'timetable' => function ($query) {
                $query->CurrentSemesterData()->with('subject:id,name', 'class_section.class', 'class_section.section');
            }
        ]);
        if (!empty($request->search)) {
            $search = $request->search;
            $sql->where(function ($query) use ($search) {
                $query->where('id', 'LIKE', "%$search%")->orwhereRaw("concat(first_name,' ',last_name) LIKE '%" . $search . "%'");
            });
        }

        if (!empty($request->class_id)) {
            $sql->whereHas('timetable.class_section.class', function ($q) use ($request) {
                $q->where('id', $request->class_id);
            });
        }

        if (!empty($request->section_id)) {
            $sql->whereHas('timetable.class_section.section', function ($q) use ($request) {
                $q->where('id', $request->section_id);
            });
        }

        if (!empty($request->subject_id)) {
            $sql->whereHas('timetable.subject', function ($q) use ($request) {
                $q->where('id', $request->subject_id);
            });
        }

        if (!empty($request->teacher_id)) {
            $sql->where('id', $request->teacher_id);
        }

        if (!empty($request->status)) {
            $sql->where('status', $request->status);
        }

        if (!empty($request->role)) {
            $sql->where('role', $request->role);
        }

        if (!empty($request->created_at)) {
            $sql->whereDate('created_at', '=', $request->created_at);
        }

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
            $operate = BootstrapTableService::button('<svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
<path d="M15.5799 11.9999C15.5799 13.9799 13.9799 15.5799 11.9999 15.5799C10.0199 15.5799 8.41992 13.9799 8.41992 11.9999C8.41992 10.0199 10.0199 8.41992 11.9999 8.41992C13.9799 8.41992 15.5799 10.0199 15.5799 11.9999Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
<path d="M12.0001 20.27C15.5301 20.27 18.8201 18.19 21.1101 14.59C22.0101 13.18 22.0101 10.81 21.1101 9.39997C18.8201 5.79997 15.5301 3.71997 12.0001 3.71997C8.47009 3.71997 5.18009 5.79997 2.89009 9.39997C1.99009 10.81 1.99009 13.18 2.89009 14.59C5.18009 18.19 8.47009 20.27 12.0001 20.27Z" stroke="white" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
</svg>
', route('timetable.teacher.show', $row->id), ['btn-eye'], ['title' => "View Timetable"]);
            $tempRow = $row->toArray();
            $timetable = $row->timetable->groupBy('day')->sortBy('start_time');
            $tempRow['no'] = $no++;
            $tempRow['Monday'] = $timetable['Monday'] ?? [];
            $tempRow['Tuesday'] = $timetable['Tuesday'] ?? [];
            $tempRow['Wednesday'] = $timetable['Wednesday'] ?? [];
            $tempRow['Thursday'] = $timetable['Thursday'] ?? [];
            $tempRow['Friday'] = $timetable['Friday'] ?? [];
            $tempRow['Saturday'] = $timetable['Saturday'] ?? [];
            $tempRow['Sunday'] = $timetable['Sunday'] ?? [];
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function teacherShow($teacherID)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        $teacher = $this->user->findById($teacherID, ['id', 'first_name', 'last_name']);
        $timetables = $this->timetable->builder()->whereHas('subject_teacher', function ($q) use ($teacherID) {
            $q->where('teacher_id', $teacherID);
        })->with('subject:id,name,bg_color', 'class_section.class', 'class_section.section', 'class_section.medium')->get();

        // Get Timetable Settings Data
        $timetableSettingsData = $this->schoolSettings->getBulkData([
            'timetable_start_time',
            'timetable_end_time',
            'timetable_duration'
        ]);
        return view('timetable.teacher.view', compact('timetables', 'teacher', 'timetableSettingsData'));
    }

    public function updateTimetableSettings(Request $request)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenRedirect('timetable-list');
        try {
            DB::beginTransaction();
            $settings = array(
                'timetable_start_time',
                'timetable_end_time',
                'timetable_duration'
            );

            // $timeTableExistsBeforeStartTime = $this->timetable->builder()->where('start_time', '<', date('H:i:s', strtotime($request->time_table_start_time)))->get();
            // if (!empty($timeTableExistsBeforeStartTime->toArray())) {
            //     ResponseService::errorResponse("Updates are prohibited as there are pre-existing lectures scheduled before " . $request->time_table_start_time);
            // }

            // $timeTableExistsAfterEndTime = $this->timetable->builder()->where('end_time', '>', date('H:i:s', strtotime($request->time_table_end_time)))->get();
            // if (!empty($timeTableExistsAfterEndTime->toArray())) {
            //     ResponseService::errorResponse("Updates are prohibited as there are pre-existing lectures scheduled after " . $request->time_table_end_time);
            // }

            $data = array();
            foreach ($settings as $row) {
                $data[] = [
                    "name" => $row,
                    "data" => $row == 'timetable_duration' ? Carbon::createFromTimestampUTC($request->$row * 60)->format('H:i:s') : date("H:i:s", strtotime($request->$row)),
                    "type" => 'time'
                ];
            }
            $this->schoolSettings->upsert($data, ["name"], ["data", "type"]);
            $this->cache->removeSchoolCache(config('constants.CACHE.SCHOOL.SETTINGS'));
            DB::commit();
            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, "Timetable Controller -> updateTimetableSettings");
            ResponseService::errorResponse();
        }
    }

    public function deleteClassTimetable($id)
    {
        ResponseService::noFeatureThenRedirect('Timetable Management');
        ResponseService::noPermissionThenSendJson('timetable-delete');
        try {
            $this->timetable->builder()->where('class_section_id', $id)->delete();
            $sessionYear = $this->cache->getDefaultSessionYear();
            $this->sessionYearsTrackingsService->deleteSessionYearsTracking('App\Models\Timetable', $id, Auth::user()->id, $sessionYear->id, Auth::user()->school_id, null);
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }
}
