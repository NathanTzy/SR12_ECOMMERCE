<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Discount extends Model
{
    protected $fillable = ['role', 'persen'];

    public function item()
{
    return $this->belongsTo(Item::class);
}
}
