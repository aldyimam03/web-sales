<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $table = 'items';

    protected $fillable = [
        'kode',
        'nama',
        'harga',
        'gambar',
    ];

    /**
     * Accessor untuk mendapatkan URL gambar.
     *
     * - Jika gambar diawali dengan http → anggap URL eksternal.
     * - Jika gambar ada di storage → generate asset('storage/...').
     * - Jika kosong → fallback ke default image.
     */
    public function getGambarUrlAttribute(): string
    {
        if ($this->gambar && str_starts_with($this->gambar, 'http')) {
            return $this->gambar; 
        }

        return $this->gambar
            ? asset('storage/' . $this->gambar)
            : asset('images/default.png');
    }
}
