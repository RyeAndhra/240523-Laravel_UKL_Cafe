<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detail extends Model
{
    use HasFactory;
    public $timestamps = false;
    protected $table="detail_transaksi";
    protected $primaryKey="id_detail_transaksi";
    protected $fillable=['id_transaksi', 'id_menu', 'qty', 'total'];
}
