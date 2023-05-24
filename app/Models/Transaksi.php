<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaksi extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table="transaksi";
    protected $primaryKey="id_transaksi";
    protected $fillable=['tgl_transaksi', 'id_user', 'id_meja', 'nama_pelanggan', 'status'];
}
