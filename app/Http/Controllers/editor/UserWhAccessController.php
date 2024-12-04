<?php

namespace App\Http\Controllers\editor;

use App\Models\UserWhAccess;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserWhAccessController extends Controller
{
    public function getData(Request $request):JsonResponse
    {
        $rescode = 200;
        $res = [];
        try {
            $rules = [
                'id' => 'required|integer|exists:users,id',
            ];
            $massages = [
                'required' => 'user wajib diisi',
                'exists' => 'user tidak ditemukan',
                'integer' => 'user tidak valid',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $id = $validData['id'];
                $access=[];
                // Ambil semua akses user dalam satu query
                $userWhAccess = UserWhAccess::select('user_wh_access.id','warehouse.name as wh_name','warehouse.id as wh_id')
                ->leftjoin('warehouse','user_wh_access.warehouse_id','warehouse.id')
                ->where('user_id', $id)->get();
                $access = $userWhAccess->toArray();
                $data = ['user' => $id, 'access'=>$access];
                $res = ['success' => 1, 'data' => $data];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }

    public function StoreData(Request $request):JsonResponse
    {
        $user = Auth::user()->id;
        $rescode = 200;
        $res = [];
        try {
            $rules = [
                'id' => 'required|integer|exists:users,id',
                'wh' =>'required|integer|exists:warehouse,id',
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'id.exists' => 'user tidak ditemukan',
                'wh.exists' => 'warehouse tidak ditemukan',
                'integer' => ':attribute tidak valid',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $id = $validData['id'];
                $wh = $validData['wh'];
                $cek = UserWhAccess::where('user_id',$id)->where('warehouse_id',$wh)->count();
                if($cek >0){
                    $res =['success'=>0,'messages'=>'Access Sudah ada'];
                }else{
                    UserWhAccess::create(['user_id'=>$id,'warehouse_id'=>$wh,'created_by'=>$user]);
                    $res =['success'=>1,'messages'=>'Success'];
                } 
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }

    public function deleteData(Request $request):JsonResponse
    {
        $user_id =Auth::user()->id;
        $rescode = 200;
        $res=[];
        try {
            $id = $request->input('id', 0);
            $query = UserWhAccess::whereIn('id',$id);
            $whacs = $query->get();
            if (count($whacs) > 0) {
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
        $user = $request->input('user', 0);
        $param = $request->input('cari', '');
        $query = UserWhAccess::select('warehouse.id', 'warehouse.name')
        ->leftjoin('warehouse','user_wh_access.warehouse_id','warehouse.id')
        ->where('user_wh_access.user_id',$user)
        ->where('warehouse.name', 'LIKE', '%'.$param.'%');
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
