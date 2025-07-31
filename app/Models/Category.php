<?php

namespace App\Models;
use App\Models\Item;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = ['nama', 'slug', 'img'];

    public function item()
{
    return $this->hasMany(Item::class);
}

    protected static function booted()
    {
        static::creating(function ($category) {
            $category->slug = Str::slug($category->nama);
        });

        static::updating(function ($category) {
            $category->slug = Str::slug($category->nama);
        });
    }
}

