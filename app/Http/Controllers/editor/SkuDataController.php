<?php

namespace App\Http\Controllers\editor;

use App\Models\SkuData;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class SkuDataController extends Controller
{
    public function index():View
    {
        return view('pages.editor.sku_data.index');
    }

    public function getData(Request $request):JsonResponse
    {
        $rescode = 200;
        $cari = $request->input('search', '');
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 10);
        try {
            $query = SkuData::select('sku_data.*','uom.name as uom_name'
            ,'sku_type.name as type_sku','category.name as category_sku')
            ->leftjoin('uom','sku_data.uom_id','uom.id')
            ->leftjoin('sku_type','sku_data.sku_type_id','sku_type.id')
            ->leftjoin('category','sku_data.category_id','category.id')
            ->where(function($q)use($cari){
                $q->where('sku_data.sku_name', 'LIKE', '%'.$cari.'%')
                ->orWhere('sku_data.sku_code', 'LIKE', '%'.$cari.'%');
            });
            $sku_data_total = $query->count();
            $sku_data = $query->offset($start)
                ->limit($limit)
                ->get();
            $data['draw'] = intval($request->input('draw'));
            $data['recordsTotal'] = $sku_data_total;
            $data['recordsFiltered'] = $sku_data_total;
            $data['data'] = $sku_data;
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
        if (!$request->filled('ket')) {
            $request->merge(['ket' => null]);
        }
        try {
            $rules = [
                'id' => 'nullable|integer',
                'sku_code' => 'required|string|max:255|unique:sku_data,sku_code,'. $request->id,
                'sku_name' => 'required|string|max:255',
                'uom' => 'required|integer|min:1',
                'type' => 'required|integer|min:1',
                'category' => 'required|integer|min:1',
                'ket' => 'nullable|string|max:255',
                'weight' => 'required|numeric',
                'height' => 'required|numeric',
                'length' => 'required|numeric',
                'width' => 'required|numeric',
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'sku_code.unique' => 'kode sku sudah ada',
                'string' => ':attribute harus bertipe string',
                'integer' => ':attribute harus bertipe angka',
                'number' => ':attribute harus bertipe angka',
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
                    'sku_code' => $validData['sku_code'],
                    'sku_name' => $validData['sku_name'],
                    'uom_id' => $validData['uom'],
                    'sku_type_id' => $validData['type'],
                    'category_id' => $validData['category'],
                    'ket' => $validData['ket'],
                    'weight' => $validData['weight'],
                    'height' => $validData['height'],
                    'length' => $validData['length'],
                    'width' => $validData['width'],
                    'created_by' => $user_id,
                    'updated_by' => $user_id,
                ];
                SkuData::updateOrCreate(['id' => $validData['id']],$data);
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
            $data = SkuData::select('sku_data.*','uom.name as uom_name'
            ,'sku_type.name as type_sku','category.name as category_sku')
            ->leftjoin('uom','sku_data.uom_id','uom.id')
            ->leftjoin('sku_type','sku_data.sku_type_id','sku_type.id')
            ->leftjoin('category','sku_data.category_id','category.id')
            ->find($id);
            if ($data) {
                $res = ['success' => 1, 'data' => $data];
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
            $query = SkuData::where('id',$id);
            $sku = $query->first();
            if ($sku) {
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

    public function generateCode():JsonResponse
    {
        $sku_code = SkuData::max('sku_code');
        $urutan = (int)substr($sku_code,-1);
        $urutan++;
        $code = sprintf("%06s",$urutan);
        return response()->json(['success'=>1,'code'=>$code]);
    }

    public function getDataSelect(Request $request):JsonResponse
    {
        $param = $request->input('cari', '');
        $query = SkuData::select('id', 'sku_name')->where('sku_name', 'LIKE', '%'.$param.'%');
        $data = $query->get();
        $data = $data->map(function ($item) {
            return [
                'id' => $item->id,
                'text' => $item->sku_name,
            ];
        });
        return response()->json($data, 200);
    }
}
