<?php

namespace App\Http\Controllers\editor;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\UserWhAccess;
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
                $userWhAccess = UserWhAccess::select('warehouse.name as wh_name','warehouse.id as wh_id')
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
}
