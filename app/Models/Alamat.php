<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Alamat extends Model
{
    protected $table = 'alamats';

    protected $fillable = [
        'user_id',
        'nama_penerima',
        'alamat_lengkap',
        'provinsi',
        'kota',
        'no_telp',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}

