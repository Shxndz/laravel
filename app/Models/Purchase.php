<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Purchase extends Model
{
    use HasFactory;

    // ✅ tambahkan 'total'
    protected $fillable = ['user_id', 'produk_id', 'quantity', 'total'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function produk()
    {
        return $this->belongsTo(Produk::class);
    }

    // ✅ auto set total kalau belum ada
    protected static function booted()
    {
        static::creating(function ($purchase) {
            if (empty($purchase->total)) {
                $produk = \App\Models\Produk::find($purchase->produk_id);
                if ($produk) {
                    $purchase->total = $produk->harga * $purchase->quantity;
                }
            }
        });
    }
}
