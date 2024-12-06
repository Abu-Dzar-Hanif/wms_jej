<?php

namespace App\Http\Controllers\editor;

use App\Models\DoDtl;
use App\Models\SkuData;
use Illuminate\View\View;
use App\Models\Transaction;
use Illuminate\Http\Request;
use App\Models\InboundRequest;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\InboundRequestDtl;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        $status = $request->input('status', 0);
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 10);
        try {
            $query = InboundRequest::select('inbound_request.*','vendor.name as vendor_name')
            ->leftjoin('vendor','inbound_request.vendor_id','vendor.id')
            ->where('inbound_request.status',$status)
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

    public function countData(Request $request):JsonResponse
    {
        $total=0;
        $cari = $request->input('search', '');
        $status = $request->input('status', 0);
        try {
            $query = InboundRequest::select('inbound_request.*','vendor.name as vendor_name')
            ->leftjoin('vendor','inbound_request.vendor_id','vendor.id')
            ->where('inbound_request.status',$status)
            ->where(function($q)use($cari){
                $q->where('inbound_request.po_number', 'LIKE', '%'.$cari.'%')
                ->orWhereNull('inbound_request.po_number')
                ->orWhere('vendor.name', 'LIKE', '%'.$cari.'%');
            });  
            $total = $query->count();
            $data =['success'=>1,'total'=>$total];
        } catch (\Throwable $th) {
            $rescode=200;
            Log::error("error ".$th);
            $data =['success'=>0,'total'=>$total,'messages'=>'terjadi kesalahan'];
        }

        return response()->json($data);
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

    public function storeData(Request $request):JsonResponse
    {
        $user_id =Auth::user()->id;
        $rescode = 200;
        $res = [];
        try {
            $rules = [
                'warehouse' => 'required|integer|min:1',
                'vendor' => 'required|integer|min:1',
                'date' => 'required|date',
                'do_number' => 'required|string|max:255',
                'po_number' => 'required|string|max:255',
                'sku_id' => 'required|array|min:1',
                'qty' => 'required|array|min:1',
                'sku_id.*' => 'required|integer|min:1',
                'qty.*' => 'required|integer|min:1',
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute harus bertipe string',
                'max' => ':attribute tidak boleh lebih dari :max',
                'integer' => ':attribute harus bertipe angka',
                'date' => ':attribute tidak valid',
                'array' => ':attribute tidak valid',
                'sku_id.*.integer' => 'sku tidak valid',
                'qty.*.integer' => 'qty tidak valid',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $inbound = InboundRequest::create([
                    'vendor_id'=>$validData['vendor'],
                    'no_sj'=>$validData['do_number'],
                    'po_number'=>$validData['po_number'],
                    'date'=>$validData['date'],
                    'warehouse_id'=>$validData['warehouse'],
                    'created_by'=>$user_id,
                ]);
                $idIn = $inbound->id;
                foreach ($validData['sku_id'] as $key => $sku) {
                    InboundRequestDtl::create([
                        'inbound_request_id' =>$idIn,
                        'sku_id' =>$sku,
                        'qty' =>$validData['qty'][$key],
                        'qty_awal' =>$validData['qty'][$key],
                        'created_by'=>$user_id,
                    ]);
                }
                $res = ['success' => 1, 'messages' => 'Success'];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }

    public function downloadInbound(Request $request)
    {
        ini_set('max_execution_time',300);
        $idIR = $request->input('id',0);
        try {
            $data = [];
            $InboundRequest = InboundRequest::select('inbound_request.*','vendor.name as vendor_name')
            ->leftjoin('vendor','inbound_request.vendor_id','=','vendor.id')
            ->where('inbound_request.id',$idIR)
            ->first();
            $InboundRequest['qrid'] = base64_encode(QrCode::format('svg')->size(100)->errorCorrection('H')->generate($InboundRequest['id']));
            $InboundRequestDtl = InboundRequestDtl::select('sku_data.sku_name','sku_data.sku_code','inbound_request_dtl.qty')
            ->leftjoin('sku_data','inbound_request_dtl.sku_id','=','sku_data.id')
            ->where('inbound_request_dtl.inbound_request_id',$idIR)
            ->get();
            $ird = $InboundRequestDtl->toArray();
            $nama_file = 'INBOUND_'.$InboundRequest->po_number.'.pdf';
            $pdf = Pdf::loadview('pages.editor.inbound_request.download_inbound',compact('InboundRequest','ird'));
            $pdf->setPaper('A4','potrait');
            return $pdf->stream($nama_file);
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
            return response()->json($res);
        }
    }
}
