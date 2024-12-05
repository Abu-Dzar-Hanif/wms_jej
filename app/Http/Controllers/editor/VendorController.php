<?php

namespace App\Http\Controllers\editor;

use App\Models\Vendor;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class VendorController extends Controller
{
    public function index():View
    {
        return view('pages.editor.vendor.index');
    }

    public function getData(Request $request):JsonResponse
    {
        $rescode = 200;
        $cari = $request->input('search', '');
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 10);
        try {
            $query = Vendor::where('name', 'LIKE', '%'.$cari.'%');
            $vendor_total = $query->count();
            $vendor = $query->offset($start)
                ->limit($limit)
                ->get();
            $data['draw'] = intval($request->input('draw'));
            $data['recordsTotal'] = $vendor_total;
            $data['recordsFiltered'] = $vendor_total;
            $data['data'] = $vendor;
            $data['success']=1;
            
        } catch (\Throwable $th) {
            $rescode=500;
            Log::error("error ".$th);
            $data =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($data,$rescode);
    }

    public function StoreUpdateData(Request $request):JsonResponse
    {
        $user_id =Auth::user()->id;
        $rescode = 200;
        $res = [];
        if (!$request->filled('id')) {
            $request->merge(['id' => null]);
        }
        try {
            $rules = [
                'id' => 'nullable|integer',
                'name' => 'required|string|max:255|unique:vendor,name,'. $request->id,
                'pic' => 'required|string|max:255',
                'phone' => 'required|string|max:255',
                'address' => 'required|string|max:255',
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'name.unique' => 'Vendor sudah ada',
                'string' => ':attribute harus bertipe string',
                'max' => ':attribute tidak boleh lebih dari :max',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $validData['created_by'] = $user_id;
                $data = [
                    'name' => $validData['name'],
                    'pic' => $validData['pic'],
                    'phone' => $validData['phone'],
                    'address' => $validData['address'],
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ];
                Vendor::updateOrCreate(['id' => $validData['id']],$data);
                $res = ['success' => 1, 'messages' => 'Success'];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }

    public function detailData(Request $request):JsonResponse
    {
        $rescode = 200;
        $res = [];
        try {
            $id = $request->input('id', 0);
            $vendor = Vendor::find($id);
            if ($vendor) {
                $res = ['success' => 1, 'data' => $vendor];
            } else {
                $res = ['success' => 0, 'messages' => 'Data tidak ditemukan'];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res, $rescode);
    }

    public function deleteData(Request $request):JsonResponse
    {
        $user_id =Auth::user()->id;
        $rescode = 200;
        $res=[];
        try {
            $id = $request->input('id', 0);
            $query = Vendor::where('id',$id);
            $vendor = $query->first();
            if ($vendor) {
                $query->update(['deleted_by'=>$user_id]);
                $query->delete();
                $res = ['success' => 1, 'messages'=>'success delete'];
            } else {
                $res = ['success' => 0, 'messages' => 'Data tidak ditemukan'];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res, $rescode);
    }

    public function getDataSelect(Request $request):JsonResponse
    {
        $param = $request->input('cari', '');
        $query = Vendor::select('id', 'name')->where('name', 'LIKE', '%'.$param.'%');
        $data = $query->get();
        $data = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->name,
            ];
        });
        return response()->json($data, 200);
    }
}
