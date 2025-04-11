<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Permintaan extends Model
{
    use HasFactory;

    protected $table = 'tbl_permintaan';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_pemesanan',
        'unit_pembuat',
        'lokasi_id',
        'file_path',
        'tanggal_dibuat',
        'supplier_id',
        'deskripsi',
        'total_payment',
        'status_id',
        'alasan_reject',
        'user_id'
    ];

    protected $casts = [
        'tanggal_dibuat' => 'date',
    ];

    // Relasi ke lokasi
    public function lokasi()
    {
        return $this->belongsTo(Lokasi::class, 'lokasi_id');
    }

    // Relasi ke supplier
    public function suplier()
    {
        return $this->belongsTo(Supplier::class, 'supplier_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke items
    public function items()
    {
        return $this->hasMany(PermintaanItem::class, 'permintaan_id');
    }

    public function status()
    {
        return $this->belongsTo(\App\Models\Status::class, 'status_id');
    }
}
