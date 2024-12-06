<?php

namespace App\Http\Controllers\api;

use Illuminate\Http\Request;
use App\Models\InboundRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\InboundRequestDtl;
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
            return response()->json($res,$rescode);
        }
    }

    public function inbound_cek_sku(Request $request):JsonResponse
    {
        try {
            return DB::transaction(function()use($request){
                $res=[];
                $rules = [
                    'inbound_request_id' => 'required|integer|exists:inbound_request,id',
                    'sku_code' => 'required|string|exists:sku_data,sku_code',
                ];
                $massages = [
                    'required' => ':attribute inbound wajib diisi',
                    'inbound_request_id.exists' => "This Inbound Request Not Found\nPlease Rescan",
                    'sku_code.exists' => "Sku Code Not Found\nPlease Rescan",
                    'integer' => ':attribute tidak valid',
                    'string' => ':attribute tidak valid',
                ];
                $data = $request->all();
                $validator = Validator::make($data, $rules, $massages);
                if ($validator->fails()){
                    $v_error = $validator->errors()->all();
                    // Jika validasi gagal, kembalikan pesan kesalahan
                    $msg=implode(' , ', $v_error);
                    $res =['success'=>0,'message'=>$msg];
                    return response()->json($res);
                }else{
                    $validData = $validator->validate();
                    $idIR = $validData['inbound_request_id'];
                    $sku_code =  $validData['sku_code'];
                    $R1 = InboundRequest::where('id',$idIR)
                    ->where('status',0)
                    ->first();
                    if(!$R1){
                        $res = ['success'=>0,'message'=> 'inbound Close!','type'=>'error','close'=>1];
                        return response()->json($res);
                    }
                    $cek = InboundRequestDtl::select('inbound_request_dtl.*','sku_data.sku_name')
                    ->join("sku_data","sku_data.id","inbound_request_dtl.sku_id")
                    ->where("inbound_request_dtl.inbound_request_id",$R1->id)
                    ->where("inbound_request_dtl.inbound_request_dtl_status",0)
                    ->where("sku_data.sku_code",$sku_code)
                    ->first();
                    if(!$cek){
                        $res = ['success'=>0,'message'=> "Sku Full Recieved\nInbound Request:".$idIR."!",'type'=>'success','close'=>0];
                        return response()->json($res);
                    }else{
                        $res = ['success'=>1,'message'=> "",'type'=>'success',"data"=>$cek,'close'=>0];
                        return response()->json($res);
                    }
                
                }
            },30);
        } catch (\Throwable $th) {
            $rescode = 500;
            Log::error("error ".$th);
            $res =['success'=>0,'message'=>'terjadi kesalahan,silahkan coba lagi','type'=>'error','close'=>1];
            return response()->json($res,$rescode);
        }
    }
}
