<?php

namespace App\Http\Controllers\editor;

use App\Models\SkuData;
use Illuminate\View\View;
use Illuminate\Http\Request;
use App\Models\InboundRequest;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use App\Models\DoDtl;
use App\Models\InboundRequestDtl;
use App\Models\Transaction;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

class InboundRequestController extends Controller
{
    public function index():View
    {
        return view('pages.editor.inbound_request.index');
    }

    public function getData(Request $request):JsonResponse
    {
        $rescode = 200;
        $cari = $request->input('search', '');
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 10);
        try {
            $query = InboundRequest::select('inbound_request.*','vendor.name as vendor_name')
            ->leftjoin('vendor','inbound_request.vendor_id','vendor.id')
            ->where(function($q)use($cari){
                $q->where('inbound_request.po_number', 'LIKE', '%'.$cari.'%')
                ->orWhereNull('inbound_request.po_number')
                ->orWhere('vendor.name', 'LIKE', '%'.$cari.'%');
            });
            $in_total = $query->count();
            $in = $query->offset($start)
                ->limit($limit)
                ->get();
            $data['draw'] = intval($request->input('draw'));
            $data['recordsTotal'] = $in_total;
            $data['recordsFiltered'] = $in_total;
            $data['data'] = $in;
            $data['success']=1;
            
        } catch (\Throwable $th) {
            $rescode=200;
            Log::error("error ".$th);
            $data =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($data,$rescode);
    }

    public function uploadDataStock(Request $request):JsonResponse
    {
        ini_set('max_execution_time', 0);
        ini_set('memory_limit', '-1');
        set_time_limit(0);
        try {
            return DB::transaction(function () use ($request) {
                $user_id =Auth::user()->id;
                $curDate = date("Y-m-d H:i:s");
                $rules = [
                    'file' => 'required|file|mimes:xlsx,xls',
                    'warehouse' => 'required|integer|min:1',
                    'ket' => 'nullable|string|max:255',
                ];
                $massages = [
                    'required' => ':attribute wajib diisi',
                    'file.mimes' => 'file yang diupload harus bertipe xlsx atau xls',
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
                    $extension = $validData['file']->getClientOriginalExtension();
                    $dir = public_path().'/temp_upload/';
                    $unikName = uniqid().'_'.time().'_'.date('Ymd');
                    $filename = $unikName.'.'.$extension;
                    $validData['file']->move($dir, $filename);
                    $address =  $dir.$filename;
                    $reader = new Xlsx();
                    $spreadsheet = $reader->load($address);
                    $sheetData = $spreadsheet->getActiveSheet()->toArray();
                    $data = [];
                    $error_msg = null;
                    $error = 0;
                    $i=0;
                    foreach($sheetData as $key => $value){
                        if($i > 0){
                            if (in_array("#N/A", $value)) {
                                $error_msg[]="Error: Nilai #N/A ditemukan pada baris ke-" . ($key + 1);
                                $error = 1;
                            }elseif(array_search(0, $value, true)){
                                $error_msg[]="Error: Nilai 0 ditemukan pada baris ke-" . ($key + 1);
                                $error = 1;
                            }else{
                                $sku_code = $value[0];
                                $qty = $value[2];
                                $cek_sku = SkuData::where('sku_code',$sku_code)->first();
                                if(!$cek_sku){
                                    $error_msg[]="Error: SKU Belum terdaftar pada baris ke-" . ($key + 1);
                                    $error = 1;
                                }else{
                                    $data[]=[
                                        "sku_id" =>$cek_sku['id'],
                                        "qty" =>$qty
                                    ];
                                }
                            }
                        }
                        $i++;
                    }
                    if (file_exists($address)) {
                        unlink($address);
                    }
                    // dd($data);
                    if($error > 0){
                        $res = ['success'=>0,'messages'=>implode(' , ',$error_msg)];
                    }else{
                        $inboundRequest = InboundRequest::create([
                            // 'wms_number'=>'111',
                            'remarks' =>$validData['ket'],
                            'date'=>$curDate,
                            'warehouse_id'=>$validData['warehouse'],
                            'created_by'=>$validData['created_by'],
                            'status'=>2,
                            'close_at'=>$curDate,
                            'close_by'=>$validData['created_by'],
                            'put_away_close_date'=>$curDate,
                            'put_away_close_by'=>$validData['created_by'],
                        ]);
                        $idIR = $inboundRequest->id;
                        foreach($data as $item){
                            $inboundRequestDtl = InboundRequestDtl::create([
                                'inbound_request_id'=>$idIR,
                                'sku_id'=>$item['sku_id'],
                                'qty'=>$item['qty'],
                                'qty_awal'=>$item['qty'],
                                'inbound_request_dtl_status'=>2,
                                'status_partial_recieved'=>$curDate,
                                'status_full_recieved'=>$curDate,
                                'created_by'=>$validData['created_by'],
                            ]);
                            $idIRD = $inboundRequestDtl->id;
                            $dodtl = DoDtl::create([
                                'inbound_request_dtl_id' =>$idIRD,
                                'sku_id' =>$item['sku_id'],
                                'qty' =>$item['qty'],
                                'qtyAct' =>$item['qty'],
                                'status' =>2,
                                'created_by'=>$validData['created_by'],
                            ]);
                            $idDoDtl = $dodtl->id;
                            DoDtl::where('id',$idDoDtl)->update([
                                'barcode'=> $inboundRequestDtl->vendor_id.$item['sku_id'].$validData['created_by'].date("YmdHis").$idDoDtl
                            ]);
                            Transaction::create([
                                'do_dtl_id'=>$idDoDtl,
                                'warehouse_id'=>$validData['warehouse'],
                                'qty'=>$item['qty'],
                                'in_by'=>$validData['created_by'],
                                'in_at'=>$curDate,
                                'alocate'=>0,
                                'alocate_at'=>$curDate,
                                'qc_status'=>1,
                                'qc_at'=>$curDate,
                                'qc_by'=>0,
                                'created_by'=>$validData['created_by'],
                            ]);
                        }
                        $res = ['success' => 1, 'messages' => 'Success'];
                    }
                }
                return response()->json($res);
            });
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
            return response()->json($res);
        }
    }
}
