<?php

namespace App\Http\Controllers\FrontEnd;

use App\Models\Item;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Discount;
use Illuminate\Support\Facades\Auth;

class FrontEndController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $userRole = $user ? $user->getRoleNames()->first() : null;

        $categories = Category::with(['item' => function ($query) {
            $query->latest()->take(4);
        }])->latest()->get();

        $latestCategories = Category::latest()->take(4)->get();

        $discount = null;

        if ($userRole) {
            $discount = \App\Models\Discount::where('role', $userRole)->first();
        }

        return view('pages.frontend.index', compact('categories', 'latestCategories', 'discount'));
    }


    public function kategori($slug)
    {
        $kategori = Category::where('slug', $slug)->firstOrFail();
        $items = $kategori->item()->latest()->paginate(6);

        $diskonPersen = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $roles = $user->getRoleNames();

            foreach ($roles as $role) {
                $diskon = Discount::where('role', $role)->first();
                if ($diskon) {
                    $diskonPersen = $diskon->persen;
                    break;
                }
            }
        }

        return view('pages.frontend.show-all', compact('kategori', 'items', 'diskonPersen'));
    }
    public function allCategories()
    {
        $categories = Category::latest()->get();
        return view('pages.frontend.show-all-categories', compact('categories'));
    }


    public function detail($id)
    {
        $item = Item::findOrFail($id);

        $hargaSetelahDiskon = null;
        $diskonPersen = 0;

        if (Auth::check()) {
            $user = Auth::user();
            $roles = $user->getRoleNames();


            foreach ($roles as $role) {
                $diskon = Discount::where('role', $role)->first();
                if ($diskon) {
                    $diskonPersen = $diskon->persen;
                    $hargaSetelahDiskon = $item->harga - ($item->harga * $diskonPersen / 100);
                    break;
                }
            }
        }

        return view('pages.frontend.detail-product', compact('item', 'hargaSetelahDiskon', 'diskonPersen'));
    }
}
