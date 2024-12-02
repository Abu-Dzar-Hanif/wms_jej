<?php

namespace App\Http\Controllers\editor;

use App\Models\SkuType;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SkuTypeController extends Controller
{
    public function index():View
    {
        return view('pages.editor.sku_type.index');
    }

    public function getData(Request $request):JsonResponse
    {
        $rescode = 200;
        $cari = $request->input('search', '');
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 10);
        try {
            $query = SkuType::where('name', 'LIKE', '%'.$cari.'%');
            $sku_type_total = $query->count();
            $sku_type = $query->offset($start)
                ->limit($limit)
                ->get();
            $data['draw'] = intval($request->input('draw'));
            $data['recordsTotal'] = $sku_type_total;
            $data['recordsFiltered'] = $sku_type_total;
            $data['data'] = $sku_type;
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
                'name' => 'required|string|max:255|unique:sku_type,name,'. $request->id,
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'name.unique' => 'SKU Type sudah ada',
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
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ];
                SkuType::updateOrCreate(['id' => $validData['id']],$data);
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
            $sku_type = SkuType::find($id);
            if ($sku_type) {
                $res = ['success' => 1, 'data' => $sku_type];
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
            $query = SkuType::where('id',$id);
            $sku_type = $query->first();
            if ($sku_type) {
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
}
