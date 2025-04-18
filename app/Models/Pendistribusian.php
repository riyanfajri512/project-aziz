<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pendistribusian extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'tbl_pendistribusian';
    protected $primaryKey = 'id';

    protected $fillable = [
        'kode_distribusi',
        'tanggal',
        'user_id',
        'unit_id',
        'deskripsi',
        'total_harga'
    ];

    protected $dates = [
        'tanggal',
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * Get the user who created this distribution
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the destination unit
     */
    public function unit()
    {
        return $this->belongsTo(Lokasi::class, 'unit_id');
    }

    /**
     * Get all items for this distribution
     */
    public function items()
    {
        return $this->hasMany(PendistribusianItem::class, 'pendistribusian_id');
    }
}
