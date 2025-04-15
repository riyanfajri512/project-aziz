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
        'qty_diterima',
        'harga',
        'total_harga',
        'belance',
    ];

    // Relasi ke Penerimaan
    public function penerimaan()
    {
        return $this->belongsTo(Penerimaan::class, 'penerimaan_id');
    }

    // Relasi ke Sparepart
    public function sparepart()
    {
        return $this->belongsTo(Sp::class, 'sparepart_id');
    }

    // Relasi ke Item Permintaan
    public function permintaanItem()
    {
        return $this->belongsTo(PermintaanItem::class, 'permintaan_item_id');
    }
}
