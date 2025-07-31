<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentProofDetail extends Model
{
    protected $fillable = [
        'payment_proof_id',
        'item_id',
        'qty',
        'harga',
        'diskon',
    ];

    protected static function booted()
    {
        static::created(function ($detail) {
            $item = $detail->item;

            if ($item && $item->kuantitas >= $detail->qty) {
                $item->decrement('kuantitas', $detail->qty);
            } else {
                throw new \Exception("Stok barang tidak mencukupi untuk item: {$item->nama}");
            }
        });
    }

    public function paymentProof()
    {
        return $this->belongsTo(PaymentProof::class, 'payment_proof_id');
    }

    public function item()
    {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function payment()
    {
        return $this->belongsTo(PaymentProof::class, 'payment_proof_id');
    }
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
