<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\InboundRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class InboundRequestController extends Controller
{
    public function cek_kode_inbound(Request $request):JsonResponse
    {
        try {
            return DB::transaction(function()use($request){
                $success = 0;
                $msg="";
                $do_dtl_id=0;
                $rules = [
                    'kode_inbound' => 'required|integer|exists:inbound_request,id',
                ];
                $massages = [
                    'required' => 'kode inbound wajib diisi',
                    'exists' => "This Inbound Request Not Found\nPlease Rescan",
                    'integer' => 'kode tidak valid',
                ];
                $data = $request->all();
                $validator = Validator::make($data, $rules, $massages);
                if ($validator->fails()){
                    $v_error = $validator->errors()->all();
                    // Jika validasi gagal, kembalikan pesan kesalahan
                    $success = 0;
                    $msg=implode(' , ', $v_error);
                    $do_dtl_id=0;
                }else{
                    $validData = $validator->validate();
                    $inbound = InboundRequest::find($validData['kode_inbound']);
                    if($inbound->status > 0){
                        if($inbound->status == 1){
                            $success = 0;
                            $msg="Inbound Request In\nPutaway!";
                        }else{
                            $success = 0;
                            $msg="This Inbound Request \nAlready Full Received!";
                        }
                    }else{
                        $success = 1;
                        $msg='';
                        $do_dtl_id=0;
                    }
                }
                $res = ['success' => $success, 'message' => $msg,'do_dtl_id'=>$do_dtl_id];
                return response()->json($res);
            },30);
        } catch (\Throwable $th) {
            $rescode = 500;
            Log::error("error ".$th);
            $res =['success'=>0,'message'=>'terjadi kesalahan','do_dtl_id'=>$do_dtl_id];
            return response()->json($res);
        }
    }
}
