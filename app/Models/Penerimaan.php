<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Penerimaan extends Model
{
    use HasFactory;
    
    protected $table = 'tbl_penerimaan';
    protected $primaryKey = 'permintaan_id'; // Tambahkan ini
    public $incrementing = false; // Karena permintaan_id bukan auto-increment
    
    protected $fillable = [
        'kode_penerimaan',
        'permintaan_id',
        'user_id',
        'tanggal',
        'grand_total',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'grand_total' => 'decimal:2',
    ];

    public function permintaan()
    {
        // Relasi ke tbl_permintaan (dari migration: references('id')->on('tbl_permintaan'))
        return $this->belongsTo(Permintaan::class, 'permintaan_id', 'id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi yang diperbaiki:
    public function items()
    {
        // Relasi ke PenerimaanItem, dimana penerimaan_id di tbl_penerimaan_items
        // merujuk ke permintaan_id di tbl_penerimaan
        return $this->hasMany(PenerimaanItem::class, 'penerimaan_id', 'permintaan_id');
    }
}