<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    protected $fillable = [
        'category_id',
        'nama',
        'img',
        'harga',
        'kuantitas',
        'deskripsi',
    ];


    public function category()
    {
        return $this->belongsTo(Category::class);
    }
    public function discounts()
    {
        return $this->hasMany(Discount::class);
    }

    public function getDiskonByRole($role)
    {
        return $this->discounts()->where('role', $role)->first()?->persen ?? 0;
    }


    public function paymentProofDetails()
    {
        return $this->hasMany(PaymentProofDetail::class, 'item_id');
    }
    public function getHargaDiskonAttribute()
{
    return $this->harga - $this->diskon; 
}
}
