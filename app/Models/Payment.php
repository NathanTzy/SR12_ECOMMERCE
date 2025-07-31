<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Payment extends Model
{
    protected $fillable = [
        'img',
        'nama_bank',
        'atas_nama',
        'no_rekening',
    ];


    public function paymentProofs(): HasMany
    {
        return $this->hasMany(PaymentProof::class);
    }
}
