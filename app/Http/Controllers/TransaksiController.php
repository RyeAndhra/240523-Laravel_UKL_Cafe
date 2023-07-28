<?php

namespace App\Http\Controllers;

use App\Models\Menu;
use App\Models\Meja;
use App\Models\Transaksi;
use App\Models\Detail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    // TRANSAKSI
    public function gettransaksi()
    {
        $transaksi = Transaksi::join('user', 'user.id_user', '=', 'transaksi.id_user')
            ->join('meja', 'meja.id_meja', '=', 'transaksi.id_meja')
            ->select(
                'transaksi.id_transaksi',
                'transaksi.tgl_transaksi',
                'transaksi.nama_pelanggan',
                'transaksi.status',
                'transaksi.total',
                'user.id_user',
                'user.nama_user',
                'user.role',
                'user.username',
                'meja.id_meja',
                'meja.nomor_meja',
            )
            ->orderBy('id_transaksi','desc')
            ->get();
        return response()->json($transaksi);
    }

    public function detailtransaksi($id)
    {
        $transaksi = Transaksi::where('id_transaksi', $id)
            ->join('user', 'user.id_user', '=', 'transaksi.id_user')
            ->join('meja', 'meja.id_meja', '=', 'transaksi.id_meja')
            ->select('transaksi.id_transaksi', 'meja.id_meja', 'transaksi.tgl_transaksi', 'user.nama_user', 'meja.nomor_meja', 'transaksi.nama_pelanggan', 'transaksi.status', 'transaksi.total')
            ->get();
        return response()->json($transaksi);
    }

    public function createtransaksi(Request $req)
    {
        $total = $req->get('total');
        $transaksi = Transaksi::create([
            'tgl_transaksi' => Carbon::now(),
            'id_user' => $req->get('id_user'),
            'id_meja' => $req->get('id_meja'),
            'nama_pelanggan' => $req->get('nama_pelanggan'),
            'status' => 'Pending',
            'total' => $total
        ]);
        if (!$transaksi) {
            return response()->json(['status' => false, 'message' => 'Gagal Membuat Pesanan']);
        }

        $cart = $req->get('cart');
        foreach ($cart as $item) {
            $detailTransaksi = Detail::create([
                'id_transaksi' => $transaksi->id_transaksi,
                'id_menu' => $item['id_menu'],
                'qty' => $item['quantity'],
                'subtotal' => $item['quantity'] * $item['harga']
            ]);
            $menu = Menu::find($item['id_menu']);
            $jumlahPesan = $menu->jumlah_pesan + $item['quantity'];
            $menu->update([
                'jumlah_pesan' => $jumlahPesan
            ]);

            if (!$detailTransaksi || !$menu) {
                $transaksi->delete();
                return response()->json(['status' => false, 'message' => 'Gagal Membuat Pesanan']);
            }
        }

        $updatemeja = Meja::where('id_meja', $req->get('id_meja'))->update([
            'status' => 'Dipakai'
        ]);
        if ($transaksi && $updatemeja) {
            return Response()->json(['status' => true, 'message' => 'Sukses Membuat Pesanan']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Membuat Pesanan']);
        }
    }

    public function payment(Request $req, $id)
    {
        $payment = Transaksi::where('id_transaksi', $id)->update([
            'status' => 'Lunas'
        ]);
        $updatemeja = Meja::where('id_meja', $req->get('id_meja'))->update([
            'status' => 'Tersedia'
        ]);
        if ($payment && $updatemeja) {
            return Response()->json(['status' => true, 'message' => 'Sukses Menyelesaikan Pesanan']);
        } else {
            return Response()->json(['status' => false, 'message' => 'Gagal Menyelesaikan Pesanan']);
        }
    }

    public function deletetransaksi($id)
    {
        $delete = Transaksi::where('id_transaksi', $id)->delete();
        if ($delete) {
            return Response()->json(['status' => true, 'message' => 'Sukses Menghapus Data Transaksi']);
        } else {
            return Response()->json(['status' => true, 'message' => 'Gagal Menghapus Data Transaksi']);
        }
    }

    // DETAIL TRANSAKSI
    public function dtransaksi($id)
    {
        $detail = Detail::where('transaksi.id_transaksi', $id)
            ->join('transaksi', 'detail_transaksi.id_transaksi', '=', 'transaksi.id_transaksi')
            ->join('menu', 'detail_transaksi.id_menu', '=', 'menu.id_menu')
            ->select('transaksi.id_transaksi', 'detail_transaksi.id_detail_transaksi', 'detail_transaksi.qty', 'detail_transaksi.subtotal', 'menu.nama_menu')
            ->get();
        return response()->json($detail);
    }

    // FILTER
    public function getTotalIncomeToday($date)
    {
        $today = Carbon::now()->format('Y-m-d');

        $totalIncomeToday = Transaksi::where('tgl_transaksi', $today)
            ->where('status', 'Lunas')
            ->join('detail_transaksi', 'transaksi.id_transaksi', '=', 'detail_transaksi.id_transaksi')
            ->sum('detail_transaksi.subtotal');
        return response()->json(['total_income_today' => $totalIncomeToday]);
    }

    public function filterIncome(Request $request)
    {
        $date = $request->input('date');
        $year = $request->input('year');
        $month = $request->input('month');

        $query = Transaksi::join('detail_transaksi', 'transaksi.id_transaksi', '=', 'detail_transaksi.id_transaksi')
            ->where('transaksi.status', 'Lunas');

        if ($date) {
            $query->where('transaksi.tgl_transaksi', $date);
        }
        if ($year && $month) {
            $query->whereYear('transaksi.tgl_transaksi', $year)
                ->whereMonth('transaksi.tgl_transaksi', $month);
        }
        $totalIncome = $query->sum('detail_transaksi.subtotal');
        return response()->json(['total_income' => $totalIncome]);
    }
}