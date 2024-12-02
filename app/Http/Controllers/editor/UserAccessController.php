<?php

namespace App\Http\Controllers\editor;

use App\Models\Menu;
use App\Models\UserAccess;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UserAccessController extends Controller
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
                $menu = Menu::all();
                $access=[];
                // Ambil semua akses user dalam satu query
                $userAccess = UserAccess::where('user_id', $id)->get()->keyBy('menu_id');
                $access = $menu->map(function ($item) use ($userAccess){
                    $defaultAccess = ['create' => 0, 'read' => 0, 'update' => 0, 'delete' => 0];
                    // Jika user memiliki akses di menu ini, update akses default
                    if ($userAccess->has($item->id)) {
                        $cekAccess = $userAccess[$item->id];
                        $defaultAccess['create'] = $cekAccess->create;
                        $defaultAccess['read'] = $cekAccess->read;
                        $defaultAccess['update'] = $cekAccess->update;
                        $defaultAccess['delete'] = $cekAccess->delete;
                    }
    
                    return [
                        'id_menu' => $item->id,
                        'name_menu' => $item->name,
                        'create' => $defaultAccess['create'],
                        'read' => $defaultAccess['read'],
                        'update' => $defaultAccess['update'],
                        'delete' => $defaultAccess['delete']
                    ];
                });
                $data = ['user' => $id, 'access'=>$access];
                $res = ['success' => 1, 'data' => $data];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }

    public function StoreUpdateData(Request $request):JsonResponse
    {
        $user = Auth::user()->id;
        $rescode = 200;
        $res = [];
        try {
            if (!$request->filled('access')) {
                $request->merge(['access' => []]);
            }
            $rules = [
                'id' => 'required|integer|exists:users,id',
                'access' =>'nullable|array',
            ];
            $massages = [
                'required' => 'user wajib diisi',
                'exists' => 'user tidak ditemukan',
                'integer' => 'user tidak valid',
                'array' => 'access tidak valid',
            ];
            $data = $request->all();
            $validator = Validator::make($data, $rules, $massages);
            $validator = Validator::make($data, $rules, $massages);
            if ($validator->fails()){
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(' , ', $v_error)];
            }else{
                $validData = $validator->validate();
                $id = $validData['id'];
                $accessData = $validData['access'];
                // dd($accessData);
                foreach ($accessData as $menuId => $access) {
                    UserAccess::updateOrCreate(
                        ['user_id' => $id, 'menu_id' => $menuId],
                        [
                            'create' => $access['create'],
                            'read' => $access['read'],
                            'update' => $access['update'],
                            'delete' => $access['delete'],
                            'created_by' => $user,
                            'updated_by' => $user,
                        ]
                    );
                }
                $res =['success'=>1,'messages'=>'Success'];
            }
        } catch (\Throwable $th) {
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }
        return response()->json($res,$rescode);
    }
}
