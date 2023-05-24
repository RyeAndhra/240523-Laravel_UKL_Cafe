<?php

namespace App\Http\Controllers;

use App\Models\Meja;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class MejaController extends Controller
{
    public function getmeja()
    {
        $meja = Meja::get();
        return response()->json($meja);
    }

    public function detailmeja($id)
    {
        $meja = Meja::where('id_meja', $id)
        ->get();
        return response()->json($meja);
    }

    public function createmeja(Request $req)
    {
        $validator = Validator::make($req->all(),[
            'nomor_meja'=>'required',
            'max'=>'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $create = Meja::create([
            'nomor_meja' =>$req->get('nomor_meja'),
            'max' =>$req->get('max'),
            'status' => 'Tersedia',
        ]);
        if($create){
            return Response()->json(['status'=>true, 'message' =>'Sukses Menambah Data Meja']);
        } else {
            return Response()->json(['status'=>false, 'message' =>'Gagal Menambah Data Meja']);
        }
    }

    public function updatemeja(Request $req, $id)
    {
        $validator = Validator::make($req->all(),[
            'nomor_meja'=>'required',
            'max'=>'required',
            'status'=>'required',
        ]);
        if($validator->fails()){
            return Response()->json($validator->errors()->toJson());
        }
        $update = Meja::where('id_meja', $id)->update([
            'nomor_meja' =>$req->get('nomor_meja'),
            'max' =>$req->get('max'),
            'status' =>$req->get('status'),
        ]);
        if($update){
            return Response()->json(['status'=>true, 'message' =>'Sukses Mengubah Data Meja']);
        } else {
            return Response()->json(['status'=>false, 'message' =>'Gagal Mengubah Data Meja']);
        }
    }

    public function deletemeja($id)
    {
        $delete = Meja::where('id_meja', $id)->delete();
        if($delete){
            return Response()->json(['status'=>true, 'message' =>'Sukses Menghapus Data Meja']);
        } else {
            return Response()->json(['status'=>true, 'message' =>'Gagal Menghapus Data Meja']);
        }
    }
}
