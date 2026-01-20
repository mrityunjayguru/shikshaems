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
                            class="dropdown-item view-syllabus"
                            data-id="' . $row->id . '">
                            View
                        </a>';
            $operate .= BootstrapTableService::menuButton('edit', route('syllabus.edit', $row->id));
            $operate .= BootstrapTableService::menuDeleteButton('delete', route('syllabus.destroy', $row->id));

            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            $tempRow['type'] = trans($row->type);
            $tempRow['eng_type'] = $row->type;
            $tempRow['created_at'] = $row->created_at;
            $tempRow['updated_at'] = $row->updated_at;
            $tempRow['operate'] = BootstrapTableService::menuItem($operate);
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
