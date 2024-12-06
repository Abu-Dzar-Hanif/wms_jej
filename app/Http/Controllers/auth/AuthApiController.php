<?php

namespace App\Http\Controllers\auth;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Log;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AuthApiController extends Controller
{
    public function authenticate(Request $request): JsonResponse
    {
        date_default_timezone_set('Asia/Jakarta');
        $rescode = 200;
        $res = [];
        try {
            $data = $request->all();
            $validator = Validator::make($data, [
                'username' => ['required'],
                'password' => ['required'],
            ]);
            if ($validator->fails()) {
                $rescode = 400;
                $v_error = $validator->errors()->all();
                // Jika validasi gagal, kembalikan pesan kesalahan
                $res = ['success' => 0, 'messages' => implode(',', $v_error)];
            } else {
                $validData = $validator->validate();
                $user = User::where('username', $validData['username'])->first();
                if ($user && Hash::check($validData['password'], $user->password)) {
                    $user->tokens()->delete();
                    $token_name = 'MobileAppToken';
                    $token = $user->createToken($token_name);
                    $text_token = $token->plainTextToken;
                    $getuser = User::with(['useraccess:user_id,menu_id,create,read,update,delete','useraccess.menu:id,name'])->find($user->id);
                    $data = ['token_type' => 'Bearer', 'token' => $text_token,'user'=>$getuser];
                    $res = ['success' => 1, 'message' => 'login berhasil', 'data' => $data];
                } else {
                    $rescode = 400;
                    $res = ['success' => 0, 'message' => 'login gagal'];
                }
            }
        }catch (\Throwable $th) {
            $rescode = 500;
            Log::error("error ".$th);
            $res =['success'=>0,'messages'=>'terjadi kesalahan'];
        }

        return response()->json($res, $rescode);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => 1, 'message' => 'success logout'], 200);
    }
}
