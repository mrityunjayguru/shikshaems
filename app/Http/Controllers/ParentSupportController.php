<?php

namespace App\Http\Controllers;

use App\Models\ParentSupport;
use Illuminate\Http\Request;
use App\Repositories\SessionYear\SessionYearInterface;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use App\Services\SessionYearsTrackingsService;
use App\Services\CachingService;

class ParentSupportController extends Controller
{
    //
    private SessionYearInterface $sessionYear;
    private SessionYearsTrackingsService $sessionYearsTrackingsService;
    private CachingService $cache;

    public function __construct(SessionYearInterface $sessionYear, SessionYearsTrackingsService $sessionYearsTrackingsService, CachingService $cache)
    {
        $this->sessionYear = $sessionYear;
        $this->sessionYearsTrackingsService = $sessionYearsTrackingsService;
        $this->cache = $cache;
    }

    public function index()
    {
        return view('parent-support.index');
    }

    public function show(Request $request)
    {
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


        $sql = ParentSupport::with('child.user')->where(function ($query) use ($search) {
            $query->when($search, function ($query) use ($search) {
                $query->where(function ($query) use ($search) {
                    $query->where('id', 'LIKE', "%$search%")->orwhere('subject', 'LIKE', "%$search%")->orwhere('message', 'LIKE', "%$search%")->orwhere('child_id', 'LIKE', "%$search%");
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
            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $rows[] = $tempRow;
        }
        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }
}
