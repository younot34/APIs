<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class UserController extends Controller
{
    //
    public function index()
    {
        $user = User::all();

        if ($user->count() > 0){
            //
            return response()->json([
                'status' => true,
                'data' => $user
            ],200);
        }else{
            //
            return response()->json([
                'status' => false,
                'message' => 'data zakat tidak ada'
            ],404);
        }

    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'name' => ['required','max:255'],
            'email' => ['required', 'email','unique:users','max:255'],
            'phone' => ['required',],
            'password' => ['required','max:255', 'confirmed']
        ],[
            'name.required' => 'Nama Hasrus Diisi',
            'name.max' => 'Panjang karakter maksimal 255',
            'email.required' => 'email harus diisi',
            'email.email' => 'email tidak valid',
            'email.unique' => 'email sudah ada',
            'email.max' => 'panjang email maksimal 255',
            'phone.required' => 'phone harus diisi',
            'password.required' => 'Password harus diisi',
            'password.max' => 'Panjang password maksimal 255',
            'password.confirmed' => 'password tidak sama',

        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }else{
            //jika ok, simpan
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;
            $user->password = Hash::make($request->password);
            $user->save();

            return response()->json([
                'status' => true,
                'message' => 'Berhasil Regist'
            ], 201);

        }
    }
    public function login(Request $request){
        $validator = Validator::make($request->all(),[
            'username' => ['required', 'email'],
            'password' => ['required']
        ],[
            'username.required' => 'Username harus diisi',
            'username.email' => 'Username menggunakan format email',
            'password.required' => 'Password harus diisi',
        ]);

        if ($validator->fails()){
            return response()->json([
                'status' => false,
                'message' => $validator->errors()
            ],400);
        }else{
            if (Auth::attempt(['email'=>$request->username, 'password'=>$request->password])){
                $user = Auth::user();
                $token = $user->createToken('authToken')->plainTextToken;

                return response()->json([
                    'status' => true,
                    'message' => 'Login Berhasil',
                    'token' => $token
                ],200);
            }else{

                return response()->json([
                    'status' => false,
                    'message' => 'Login Gagal'
                ],400);
            }
        }
    }

    public function show(Request $id)
    {
        //
        $user = User::find($id);
        if ($user != null){
            //
            return response()->json([
                'status' => true,
                'data' => $user
            ],200);
        }else{
            //
            return response()->json([
                'status' => false,
                'message' => 'data user tidak ada'
            ],404);
        }
    }

    public function logout(){
        Auth::user()->tokens()->delete();

        return response()->json([
            'status' => true,
            'message' => 'Berhasil logout'
        ]);
    }
}
