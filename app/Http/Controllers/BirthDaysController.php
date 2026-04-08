<?php

namespace App\Http\Controllers;

use App\Models\Students;
use App\Repositories\User\UserInterface;
use App\Repositories\ClassSchool\ClassSchoolInterface;
use App\Repositories\Section\SectionInterface;
use App\Repositories\ClassSection\ClassSectionInterface;
use App\Services\BootstrapTableService;
use App\Services\CachingService;
use App\Services\ResponseService;
use App\Services\UploadService;
use App\Services\SessionYearsTrackingsService;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Throwable;
use Carbon\Carbon;

class BirthDaysController extends Controller
{
    protected UserInterface $user;
    private ClassSchoolInterface $class;
    private SectionInterface $section;
    private ClassSectionInterface $classSection;
    private SessionYearsTrackingsService $sessionYearsTrackingsService;
    private CachingService $cache;

    public function __construct(UserInterface $user, ClassSchoolInterface $class, SectionInterface $section, ClassSectionInterface $classSection, SessionYearsTrackingsService $sessionYearsTrackingsService, CachingService $cache)
    {
        $this->user = $user;
        $this->class = $class;
        $this->section = $section;
        $this->classSection = $classSection;
        $this->sessionYearsTrackingsService = $sessionYearsTrackingsService;
        $this->cache = $cache;
    }

    public function index()
    {
        $classes = $this->class->all(['id', 'name', 'medium_id'], ['stream', 'medium']);
        $sections = $this->section->builder()->orderBy('name', 'ASC')->get();

        $class_sections = $this->classSection->all(['*'], ['class', 'class.stream', 'section', 'medium']);

        return view('birthdays.index', compact('classes', 'class_sections'));
    }

    public function show(Request $request)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $operate = '';

        $month = $request->month ?? Carbon::now()->month;
        // dd($request->all());
        $sql = Students::with('user.extra_student_details.form_field', 'guardian', 'class_section.class.stream', 'class_section.section', 'class_section.class.shift', 'class_section.medium')
            ->whereHas('user', function ($q) use ($month) {
                $q->whereMonth('dob', $month);
            });
        if ($request->class_id) {
            $sql->whereHas('class_section', function ($q) use ($request) {
                $q->where('class_id', $request->class_id);
            });
        }


        if ($request->class_section_id) {
            $sql->where('class_section_id', $request->class_section_id);
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
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
}
