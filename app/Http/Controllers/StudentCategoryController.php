<?php

namespace App\Http\Controllers;

use App\Models\StudentCategory;
use App\Models\Students;
use App\Services\BootstrapTableService;
use App\Services\ResponseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Throwable;

class StudentCategoryController extends Controller
{

    public function index()
    {
        return view('student-category.index');
    }

    public function store(Request $request)
    {
        // ResponseService::noPermissionThenSendJson('student-diary-create');
        $request->validate(
            [
                'name' => 'required',
                'certificate' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf|max:5120',
            ],
            [
                'certificate.mimetypes' => 'The certificate must be a file of type: image or pdf.'
            ]
        );

        try {
            DB::beginTransaction();

            $data = new StudentCategory();
            $data->name = $request->name;
            if ($request->hasFile('certificate')) {
                $file = $request->file('certificate');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('2/student-category/certificate', $filename, 'public'); // storage/app/public/student-category
                $data->certificate = $path;
            }
            $data->save();

            DB::commit();
            ResponseService::successResponse('Data Stored Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'Student Category Controller -> Store method');
            ResponseService::errorResponse();
        }
    }

    public function show(Request $request)
    {
        $offset = request('offset', 0);
        $limit = request('limit', 10);
        $sort = request('sort', 'id');
        $order = request('order', 'DESC');
        $search = request('search');
        $sql = StudentCategory::where(function ($query) use ($search) {
            $query->when($search, function ($query) use ($search) {
                $query->where('id', 'LIKE', "%$search%")
                    ->orwhere('name', 'LIKE', "%$search%");
            });
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
            $operate = '';

            $operate .= BootstrapTableService::editButton(route('students.category.update', $row->id), true);
            $operate .= BootstrapTableService::deleteButton(route('students.category.destroy', $row->id));

            $tempRow = $row->toArray();
            $tempRow['no'] = $no++;
            // $tempRow['operate'] = BootstrapTableService::menuItem($operate);
            $tempRow['operate'] = $operate;
            $rows[] = $tempRow;
        }

        $bulkData['rows'] = $rows;
        return response()->json($bulkData);
    }

    public function update(Request $request, $id)
    {
        $request->validate(
            [
                'name' => 'required',
                'certificate' => 'nullable|file|mimetypes:image/jpeg,image/png,application/pdf|max:5120',
            ],
            [
                'certificate.mimetypes' => 'The certificate must be a file of type: image or pdf.'
            ]
        );
        try {
            DB::beginTransaction();

            $data = StudentCategory::where('id', $id)->first();
            $data->name = $request->name;
            if ($request->hasFile('certificate')) {
                $file = $request->file('certificate');
                $filename = time() . '_' . $file->getClientOriginalName();
                $path = $file->storeAs('2/student-category/certificate', $filename, 'public'); // storage/app/public/student-category
                $data->certificate = $path;
            }
            $data->save();

            DB::commit();
            ResponseService::successResponse('Data Updated Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'Student Category Controller -> Store method');
            ResponseService::errorResponse();
        }
    }

    public function destroy($id)
    {
        try {
            DB::beginTransaction();
            // $existing_data = Students::where('student_category_id', $id)->get();
            // if (count($existing_data) > 0) {
            //     return ResponseService::errorResponse('This Category is already used in Students. You can not delete this.');
            // }
            StudentCategory::where('id', $id)->delete();
            DB::commit();
            ResponseService::successResponse('Data Deleted Successfully');
        } catch (Throwable $e) {
            DB::rollBack();
            ResponseService::logErrorResponse($e, 'Student Category Controller -> Destroy method');
            ResponseService::errorResponse();
        }
    }
}
