<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PermintaanItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_permintaan_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'permintaan_id',
        'kode_sparepart',
        'jenis_kendaraan',
        'nama_sparepart',
        'qty',
        'harga',
        'total_harga'
    ];

    // Relasi ke permintaan
    public function permintaan()
    {
        return $this->belongsTo(Permintaan::class, 'permintaan_id');
    }
}
