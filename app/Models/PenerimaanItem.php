<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenerimaanItem extends Model
{
    use HasFactory;
    protected $table = 'tbl_penerimaan_items';

    protected $fillable = [
        'penerimaan_id',
        'kode_sparepart',
        'jenis_kendaraan',
        'nama_sparepart',
        'qty',
        'harga',
        'total_harga',
        'belance',
    ];
    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id', 'permintaan_id');
    }
}
