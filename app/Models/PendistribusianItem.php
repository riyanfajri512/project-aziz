<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PendistribusianItem extends Model
{
    use HasFactory;

    protected $table = 'tbl_pendistribusian_items';
    protected $primaryKey = 'id';

    protected $fillable = [
        'pendistribusian_id',
        'sparepart_id',
        'kode_sparepart',
        'jenis_kendaraan',
        'nama_sparepart',
        'stok_tersedia',
        'qty_distribusi',
        'harga',
        'total'
    ];

    /**
     * Get the distribution this item belongs to
     */
    public function pendistribusian()
    {
        return $this->belongsTo(Pendistribusian::class, 'pendistribusian_id');
    }

    /**
     * Get the sparepart details
     */
    public function sparepart()
    {
        return $this->belongsTo(Sp::class, 'sparepart_id');
    }
}
