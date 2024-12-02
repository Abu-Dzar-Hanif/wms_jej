<?php

namespace App\Http\Controllers\editor;

use App\Models\User;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index():View
    {
        return view('pages.editor.user.index');
    }

    public function getData(Request $request):JsonResponse
    {
        $rescode = 200;
        $cari = $request->input('search', '');
        $start = $request->input('start', 0);
        $limit = $request->input('limit', 10);
        try {
            $query = User::where('name', 'LIKE', '%'.$cari.'%');
            $user_total = $query->count();
            $user = $query->offset($start)
                ->limit($limit)
                ->get();
            $data['draw'] = intval($request->input('draw'));
            $data['recordsTotal'] = $user_total;
            $data['recordsFiltered'] = $user_total;
            $data['data'] = $user;
            $data['success']=1;
            
        } catch (\Throwable $th) {
            $rescode=500;
            Log::error("error ".$th);
            $data =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($data,$rescode);
    }

    public function storeData(Request $request):JsonResponse
    {
        $user_id =Auth::user()->id;
        $rescode = 200;
        $res = [];
        try {
            $rules = [
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255|unique:users,username',
                'email' => 'required|unique:users,email|email:dns|max:255',
                'password' => 'required|string|min:8|confirmed',
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute harus bertipe string',
                'max' => ':attribute tidak boleh lebih dari :max',
                'email' => 'Email tidak valid',
                'email.dns' => 'Format email tidak valid',
                'email.unique' => 'Email sudah digunakan, silakan pilih yang lain',
                'username.unique' => 'Username sudah digunakan, silakan pilih yang lain',
                'password.min' => 'Password minimal 8 karakter',
                'password.confirmed' => 'Konfirmasi password tidak cocok',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $validData['password'] = Hash::make($validData['password']);
                $validData['created_by'] = $user_id;
                User::create($validData);
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
            $user = User::find($id);
            if ($user) {
                $res = ['success' => 1, 'data' => $user];
            } else {
                $res = ['success' => 0, 'messages' => 'Data tidak ditemukan'];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }

    public function updateData(Request $request):JsonResponse
    {
        $user_id =Auth::user()->id;
        $rescode = 200;
        $res = [];
        try {
            $rules = [
                'id' => 'required|integer',
                'name' => 'required|string|max:255',
                'username' => 'required|string|max:255',
                'email' => 'required|email:dns|max:255',
            ];
            $massages = [
                'required' => ':attribute wajib diisi',
                'string' => ':attribute harus bertipe string',
                'max' => ':attribute tidak boleh lebih dari :max',
                'email' => 'Email tidak valid.',
                'email.dns' => 'Format email tidak valid.',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $query = User::where('id',$validData['id']);
                $user = $query->first();
                if($user){
                    $validData['updated_by'] = $user_id;
                    $query->update($validData);
                    $res = ['success' => 1, 'messages' => 'Success'];
                }else{
                    $res = ['success' => 0, 'messages' => 'Data tidak ditemukan'];
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
            $query = User::where('id',$id);
            $user = $query->first();
            if ($user) {
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
        return response()->json($res,$rescode);
    }
}
