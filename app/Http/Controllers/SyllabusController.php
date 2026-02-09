<?php

namespace App\Http\Controllers;

use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use App\Services\CachingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Rules\MaxFileSize;
use Throwable;
use App\Models\ClassSchool;
use App\Models\Subject;
use App\Models\Syllabus;
use App\Models\SyllabusContent;
use Illuminate\Support\Facades\DB;


class SyllabusController extends Controller
{
    public function index()
    {
        $classes = ClassSchool::get();
        $subjects = Subject::get();
        return view('syllabus.index', compact('classes', 'subjects'));
    }

    public function show(Request $request)
    {
        $offset = $request->get('offset', 0);
        $limit = $request->get('limit', 10);
        $sort = $request->get('sort', 'id');
        $order = $request->get('order', 'DESC');
        $search = $request->get('search');
        $showDeleted = $request->show_deleted;

        $sql = Syllabus::with('class', 'contents', 'subject')
            ->when($search, function ($q) use ($search) {
                $q->where('id', 'LIKE', "%$search%")
                    ->orWhereHas('contents', function ($qc) use ($search) {
                        $qc->where('title', 'LIKE', "%$search%")
                            ->orWhere('description', 'LIKE', "%$search%");
                    })
                    ->Owner();
            })
            ->when(!empty($showDeleted), function ($q) {
                $q->onlyTrashed()->Owner();
            });

        if ($request->filled('class_id')) {
            $sql->where('class_id', $request->class_id);
        }

        $total = $sql->count();

        if ($offset >= $total && $total > 0) {
            $offset = floor(($total - 1) / $limit) * $limit;
        }

        $res = $sql->orderBy($sort, $order)
            ->skip($offset)
            ->take($limit)
            ->get();

        $rows = [];
        $no = $offset + 1;

        foreach ($res as $row) {
            $operate = '<a href="javascript:void(0)"
                            class="btn btn-sm btn-rounded btn-success view-syllabus text-white me-2"
                            data-id="' . $row->id . '">
                            <i class="fa fa-eye me-3" style="margin-left: -2px;"></i>
                        </a>';
            $operate .= '<a href="' . route('syllabus.edit', $row->id) . '"
                            class="btn btn-xs btn-rounded btn-icon btn-warning me-2"
                            data-id="' . $row->id . '">
                            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M11 2H9C4 2 2 4 2 9V15C2 20 4 22 9 22H15C20 22 22 20 22 15V13" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M16.04 3.02001L8.16 10.9C7.86 11.2 7.56 11.79 7.5 12.22L7.07 15.23C6.91 16.32 7.68 17.08 8.77 16.93L11.78 16.5C12.2 16.44 12.79 16.14 13.1 15.84L20.98 7.96001C22.34 6.60001 22.98 5.02001 20.98 3.02001C18.98 1.02001 17.4 1.66001 16.04 3.02001Z" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M14.91 4.1499C15.58 6.5399 17.45 8.4099 19.85 9.0899" stroke="currentColor" stroke-width="1.5" stroke-miterlimit="10" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </a>';
            // $operate .= BootstrapTableService::editButton(route('syllabus.edit', $row->id, ['data-id' => $row->id]));
            $operate .= BootstrapTableService::deleteButton('delete', route('syllabus.destroy', $row->id));

            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['type'] = trans($row->type);
            $tempRow['eng_type'] = $row->type;
            $tempRow['created_at'] = $row->created_at;
            $tempRow['updated_at'] = $row->updated_at;
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        return response()->json([
            'total' => $total,
            'rows' => $rows
        ]);
    }


    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'class_id' => 'required|numeric',
            'subject_id' => 'required|numeric',
            'syllabus_contents' => 'required|array|min:1',
            'syllabus_contents.*.title' => 'required|string',
            'syllabus_contents.*.description' => 'required|string',
        ]);

        if ($validator->fails()) {
            ResponseService::errorResponse($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            // 1️⃣ Store in syllabus table
            $syllabus = Syllabus::create([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
            ]);

            // 2️⃣ Store in syllabus_contents table
            foreach ($request->syllabus_contents as $content) {
                SyllabusContent::create([
                    'syllabus_id' => $syllabus->id,
                    'title' => $content['title'],
                    'description' => $content['description'],
                ]);
            }

            DB::commit();


            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }

    public function edit($id)
    {
        $syllabus = Syllabus::with(['class', 'subject', 'contents'])->findOrFail($id);
        $classes = ClassSchool::get();
        $subjects = Subject::get();
        return view('syllabus.edit', compact('syllabus', 'classes', 'subjects'));
    }

    public function details($id)
    {
        $syllabus = Syllabus::with(['class', 'subject', 'contents'])->findOrFail($id);
        return response()->json($syllabus);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'class_id' => 'required',
            'subject_id' => 'required',
            'syllabus_contents.*.title' => 'required',
            'syllabus_contents.*.description' => 'required',
        ]);

        DB::transaction(function () use ($request, $id) {

            $syllabus = Syllabus::findOrFail($id);
            $syllabus->update([
                'class_id' => $request->class_id,
                'subject_id' => $request->subject_id,
            ]);

            // Remove old contents
            SyllabusContent::where('syllabus_id', $id)->delete();

            // Insert updated contents
            foreach ($request->syllabus_contents as $content) {
                SyllabusContent::create([
                    'syllabus_id' => $id,
                    'title' => $content['title'],
                    'description' => $content['description'],
                ]);
            }
        });

        // return redirect()->route('syllabus.index')
        //     ->with('success', 'Syllabus updated successfully');
        ResponseService::successResponse('Syllabus updated Successfully');
    }

    public function destroy($id)
    {
        try {
            $syllabus = Syllabus::with('contents')->findOrFail($id);

            DB::transaction(function () use ($syllabus) {
                $syllabus->contents()->delete(); // soft delete contents
                $syllabus->delete(); // soft delete syllabus
            });

            ResponseService::successResponse('Syllabus Deleted Successfully');
        } catch (\Throwable $e) {
            ResponseService::logErrorResponse($e);
            ResponseService::errorResponse();
        }
    }
}
