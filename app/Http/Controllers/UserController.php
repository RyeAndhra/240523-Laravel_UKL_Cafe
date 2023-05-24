<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    public function getuser()
    {
        $user = User::get();
        return response()->json($user);
    }

    public function detailuser($id)
    {
        $user = User::where('id_user', $id)
            ->get();
        return response()->json($user);
    }

    public function getrole($role)
    {
        $user = User::where('role', $role)
            ->get();
        return response()->json($user);
    }

    public function createuser(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nama_user' => 'required',
            'role' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return Response()->json($validator->errors()->toJson());
        }
        $create = User::create([
            'nama_user' => $req->get('nama_user'),
            'role' => $req->get('role'),
            'username' => $req->get('username'),
            'password' => Hash::make($req->get('password')),
        ]);
        if ($create) {
            return Response()->json(['status' => true, 'message' => 'Sukses Menambah Data User']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Menambah Data User']);
        }
    }

    public function updateuser(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'nama_user' => 'required',
            'role' => 'required',
            'username' => 'required',
            'password' => 'required',
        ]);
        if ($validator->fails()) {
            return Response()->json($validator->errors()->toJson());
        }
        $update = User::where('id_user', $id)->update([
            'nama_user' => $req->get('nama_user'),
            'role' => $req->get('role'),
            'username' => $req->get('username'),
            'password' => Hash::make($req->get('password')),
        ]);
        if ($update) {
            return Response()->json(['status' => true, 'message' => 'Sukses Mengubah Data User']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Mengubah Data User']);
        }
    }

    public function deleteuser($id)
    {
        $delete = User::where('id_user', $id)->delete();
        if ($delete) {
            return Response()->json(['status' => true, 'message' => 'Sukses Menghapus Data User']);
        } else {
            return Response()->json(['status' => true, 'message' => 'Gagal Menghapus Data User']);
        }
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        try {
            if (!$token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'invalid_credentials'], 400);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'could_not_create_token'], 500);
        }
        return response()->json(['status' => 'Success', 'token' => $token], );
        return response()->json(compact('token'));
    }

    public function getAuthenticatedUser()
    {
        try {
            if (!$user = JWTAuth::parseToken()->authenticate()) {
                return response()->json(['user_not_found'], 404);
            }
        } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e) {
            return response()->json(['token_expired'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e) {
            return response()->json(['token_invalid'], $e->getStatusCode());
        } catch (Tymon\JWTAuth\Exceptions\JWTException $e) {
            return response()->json(['token_absent'], $e->getStatusCode());
        }
        return response()->json(compact('user'));
    }
}