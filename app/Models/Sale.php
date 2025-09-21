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
}
