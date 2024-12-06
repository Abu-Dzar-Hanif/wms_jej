<?php

namespace App\Http\Controllers\api;

use App\Models\DoDtl;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\InboundRequest;
use App\Models\InboundRequestDtl;
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

    public function inbound_register_dtl(Request $request):JsonResponse
    {
        try {
            return DB::transaction(function () use ($request) {
                $request->input('status','1');
                $request->input('qty','0');
                $rules = [
                    'inbound_request_id' => 'required|integer|exists:inbound_request,id',
                    'inbound_request_dtl_id' => 'required|integer|exists:inbound_request_dtl,id',
                    'sku_code' => 'required|string|exists:sku_data,sku_code',
                    'user_id' => 'required|integer|exists:users,id',
                    'status' => 'required|string',
                    'qty' => 'required|string',
                ];
                $massages = [
                    'required' => ':attribute inbound wajib diisi',
                    'inbound_request_id.exists' => "This Inbound Request Not Found\nPlease Rescan",
                    'sku_code.exists' => "Sku Code Not Found\nPlease Rescan",
                    'user_id.exists' => "User Not Found",
                    'integer' => ':attribute tidak valid',
                    'string' => ':attribute tidak valid',
                ];
                $data = $request->all();
                $res =[];
                $status_message = "";
                $validator = Validator::make($data, $rules, $massages);
                if ($validator->fails()){
                    $v_error = $validator->errors()->all();
                    // Jika validasi gagal, kembalikan pesan kesalahan
                    $msg=implode(' , ', $v_error);
                    $res =['success'=>0,'message'=>$msg];
                }else{
                    $validData = $validator->validate();
                    $idIR = $validData['inbound_request_id'];
                    $idIRD = $validData['inbound_request_dtl_id'];
                    $status = $validData['status'];
                    $qty = $validData['qty'];
                    $user_id = $validData['user_id'];
                    $Ird = InboundRequestDtl::where("id", $idIRD)->first();
                    if($Ird->inbound_request_dtl_status == 2){
                        $res = ["success" => 0, "message" => "Sku Full Received : ".$Ird->status_full_recieved];
                    }else{
                        $Ird->inbound_request_dtl_status = $status;
                        $sDate = date("Y-m-d H:i:s");
                        if($status == "2"){
                            $Ird->status_full_recieved = $sDate;
                            if(!$Ird->status_partial_recieved){
                                $Ird->status_partial_recieved = $sDate;
                            }
                            $status_message = "Sku Full Received : ".$sDate;
                        }else{
                            $Ird->status_partial_recieved = $sDate;
                            $status_message = "Sku Partial Received : ".$sDate;
                        }
                        $Ird->qty = $qty;
                        $Ird->updated_by =$user_id;
                        $Ird->save();
                        if($status == "2"){
                            $cek_In = InboundRequest::where("id", $idIR)->first();
                            $request->initialize(['inbound_request_dtl_id' => $idIRD, 'curId' => $user_id]);
                            // if($cek_In->inbound_request_type == "retur"){
                            //     $do1 = $this->alocate_retur($request);
                            // }else{
                            //     $do1 = $this->alocate_v2($request);
                            // }
                            $do1 = $this->alocate_v2($request);
                            if (isset($do1['throwable'])){
                                throw new \Exception($do1['message']);
                            }else{
                                if($do1['success'] == 1){
                                    if($do1['temp_rack'] == 1){
                                        $res = $do1;
                                    } else {
                                        // cek apakah status semua sku inbound request dtl sudah jadi 2 semua
                                        $R = InboundRequestDtl::where("inbound_request_id", $idIR)->get();
                                        $total_status2 = 2*count($R);
                                        
                                        $current_status2 = 0;
                                        foreach($R as $k => $v){
                                            $current_status2 += $v->inbound_request_dtl_status;
                                        }
                                        //kalo inbound_request_dtl_status udah 2 semua
                                        // update inbound_request ke status 2
                                        $R2 = InboundRequest::where("id", $idIR)->first();
                                        if($current_status2 == $total_status2){
                                            $R2->status = 1;
                                            $R2->close_at = date("Y-m-d H:i:s");
                                            $R2->close_by = $user_id;
                                            $status_message = "Inbound Request Full Received";
                                            // $tl = new InboundRequestTl;
                                            // $tl->inbound_request_id = $inbound_request_id;
                                            // $tl->user = $user_id;
                                            // $tl->status = 1;
                                            // $tl->save();
                                        } else {
                                            $R2->status = 0;
                                        }
                                        $R2->save();
                                        $res = array("success" => 1, "message" => $status_message);
                                    }
                                } else {
                                    $res = $do1;
                                }
                            }
                        }else{
                            $res = array("success" => 1, "message" => "Sku Partial Received : ".date("Y-m-d H:i:s"));
                        }
                    }
                }
                return response()->json($res, 200);
            },30);
        } catch (\Throwable $th) {
            $rescode = 500;
            Log::error("error ".$th);
            $res =['success'=>0,'message'=>'terjadi kesalahan,silahkan coba lagi'];
            return response()->json($res,$rescode);
        }
    }

    public function alocate_v2(Request $request)
    {
        try {
            return DB::transaction(function () use ($request) {
                $inbound_request_dtl_id = $request->input('inbound_request_dtl_id','0');
                $curId = $request->input('curId',"0");
                $curDate = date("Y-m-d H:i:s");
                $r = InboundRequest::select('b.*', 'inbound_request.warehouse_id','inbound_request.vendor_id', 'c.height','c.length','c.width', 'c.weight')
                ->leftjoin("inbound_request_dtl as b", function($query){
                    $query->on("b.inbound_request_id","inbound_request.id");
                })
                ->leftjoin("sku_data as c", function($q){
                    $q->on("c.id","b.sku_id");
                })
                ->leftjoin('warehouse as d', "inbound_request.warehouse_id", "d.id")
                ->where('b.id', $inbound_request_dtl_id)
                ->first();
                // dd($r->toArray());
                $qty_sku = $r->qty;
                $dodtl = DoDtl::create([
                    'inbound_request_dtl_id' =>$r->id,
                    'sku_id' =>$r->sku_id,
                    'qty' =>$qty_sku,
                    'qtyAct' =>$qty_sku,
                    'status' =>0,
                    'created_by'=>$curId,
                ]);
                $idDoDtl = $dodtl->id;
                DoDtl::where('id',$idDoDtl)->update([
                    'barcode'=> $r->vendor_id.$r->sku_id.$curId.date("YmdHis").$idDoDtl
                ]);
                $tr = Transaction::create([
                    'do_dtl_id'=>$idDoDtl,
                    'warehouse_id'=>$r->warehouse_id,
                    'qty'=>$qty_sku,
                    'in_by'=>$curId,
                    'in_at'=>$curDate,
                    'alocate'=>1,
                    'qc_status'=>1,
                    'qc_at'=>$curDate,
                    'qc_by'=>0,
                    'created_by'=>$curId,
                ]);
                $res = array(
                    "success" => 1, 
                    "message" => "Sku FUll Recieved To Rack at warehouse :".$r->warehouse_id, 
                    "inbound_request_dtl_id" => 0, 
                    "temp_rack" => 0,
                    "qty_sisa" => 0
                );
                return $res;
            },30);
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'message'=>'terjadi kesalahan,silahkan coba lagi',"throwable" => 1];
            return $res;
        }
    }
}
