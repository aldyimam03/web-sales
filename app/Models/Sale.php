<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    protected $table = 'sales';

    protected $fillable = [
        'kode_penjualan',
        'tanggal_penjualan',
        'status',
        'total_harga',
        'user_id',
    ];

    protected $casts = [
        'tanggal_penjualan' => 'date',
    ];

    public function items()
    {
        return $this->hasMany(SaleItem::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalDibayarAttribute()
    {
        return $this->payments()->sum('jumlah');
    }

    public function getSisaPembayaranAttribute()
    {
        return $this->total_harga - $this->total_dibayar;
    }

    public function updateStatus()
    {
        if ($this->total_dibayar == 0) {
            $this->update(['status' => 'Belum Dibayar']);
        } elseif ($this->total_dibayar < $this->total_harga) {
            $this->update(['status' => 'Belum Dibayar Sepenuhnya']);
        } else {
            $this->update(['status' => 'Sudah Dibayar']);
        }
    }
}
