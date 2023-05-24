<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MenuController extends Controller
{
    public function getmenu()
    {
        $menu = Menu::get();
        return response()->json($menu);
    }

    public function detailmenu($id)
    {
        $menu = Menu::where('id_menu', $id)
            ->get();
        return response()->json($menu);
    }

    public function createmenu(Request $req)
    {
        $validator = Validator::make($req->all(), [
            'nama_menu' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
            'harga' => 'required'
        ]);
        if ($validator->fails()) {
            return Response()->json($validator->errors()->toJson());
        }
        $imageName = time() . '.' . $req->gambar->extension();
        $req->gambar->move(public_path('Menu'), $imageName);
        // $image_path = $req->file('gambar')->store('Images', 'public');
        $create = Menu::create([
            'nama_menu' => $req->get('nama_menu'),
            'jenis' => $req->get('jenis'),
            'deskripsi' => $req->get('deskripsi'),
            'gambar' => $imageName,
            'harga' => $req->get('harga'),
            'jumlah_pesan' => 0
        ]);
        if ($create) {
            return Response()->json(['status' => true, 'message' => 'Sukses Menambah Data Menu']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Menambah Data Menu']);
        }
    }

    public function updatemenu(Request $req, $id)
    {
        $validator = Validator::make($req->all(), [
            'nama_menu' => 'required',
            'jenis' => 'required',
            'deskripsi' => 'required',
            'harga' => 'required'
        ]);
        if ($validator->fails()) {
            return Response()->json($validator->errors()->toJson());
        }
        $update = Menu::where('id_menu', $id)->update([
            'nama_menu' => $req->get('nama_menu'),
            'jenis' => $req->get('jenis'),
            'deskripsi' => $req->get('deskripsi'),
            'harga' => $req->get('harga')
        ]);
        if ($update) {
            return Response()->json(['status' => true, 'message' => 'Sukses Mengubah Data Menu']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Mengubah Data Menu']);
        }
    }
    public function updatephoto(Request $req, $id)
    {
        $imageName = time() . '.' . $req->gambar->extension();
        $req->gambar->move(public_path('Menu'), $imageName);

        $update = Menu::where('id_menu', $id)->update([
            'gambar' => $imageName,
        ]);
        if ($update) {
            return Response()->json(['status' => true, 'message' => 'Sukses Mengubah Foto Menu']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Mengubah Foto Menu']);
        }
    }

    public function deletemenu($id)
    {
        $delete = Menu::where('id_menu', $id)->delete();
        if ($delete) {
            return Response()->json(['status' => true, 'message' => 'Sukses Menghapus Data Menu']);
        } else {
            return Response()->json(['status' => true, 'message' => 'Gagal Menghapus Data Menu']);
        }
    }
}