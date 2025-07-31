<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class PaymentProof extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'payment_id',
        'bukti_tf',
        'nama_penerima',
        'alamat',
        'kota',
        'provinsi',
        'telp',
        'subtotal',
        'diskon_persen',
        'total',
        'status',
        'ongkir',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function payment()
    {
        return $this->belongsTo(Payment::class);
    }

    public function details()
    {
        return $this->hasMany(PaymentProofDetail::class, 'payment_proof_id');
    }
}

