<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Discount;
use App\Models\Item;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

/**
 * Controller untuk menampilkan halaman frontend:
 * - Beranda
 * - Daftar kategori
 * - Detail kategori
 * - Detail produk
 */
class FrontEndController extends Controller
{
    /**
     * Halaman beranda.
     * Menampilkan kategori terbaru beserta item terbaru di setiap kategori (4 item per kategori).
     * Jika user login, akan mengambil diskon sesuai role user.
     */
    public function index()
    {
        $user = Auth::user();
        $userRole = $user ? $user->getRoleNames()->first() : null;

        $categories = Category::with([
            'item' => fn ($query) => $query->latest()->take(4)
        ])->latest()->get();

        $latestCategories = Category::latest()->take(4)->get();

        $discount = $userRole
            ? Discount::where('role', $userRole)->first()
            : null;

        return view('pages.frontend.index', compact('categories', 'latestCategories', 'discount'));
    }

    /**
     * Halaman detail kategori.
     * Menampilkan semua item di kategori tersebut dengan pagination 6 item per halaman.
     * Jika user login, akan menghitung diskon sesuai role user.
     *
     * @param string $slug Slug kategori
     */
    public function kategori(string $slug)
    {
        $kategori = Category::where('slug', $slug)->firstOrFail();
        $items = $kategori->item()->latest()->paginate(6);

        $diskonPersen = 0;

        if (Auth::check()) {
            foreach (Auth::user()->getRoleNames() as $role) {
                $diskon = Discount::where('role', $role)->first();
                if ($diskon) {
                    $diskonPersen = $diskon->persen;
                    break;
                }
            }
        }

        return view('pages.frontend.show-all', compact('kategori', 'items', 'diskonPersen'));
    }

    /**
     * Halaman semua kategori.
     * Menampilkan daftar semua kategori terbaru.
     */
    public function allCategories()
    {
        $categories = Category::latest()->get();
        return view('pages.frontend.show-all-categories', compact('categories'));
    }

    /**
     * Halaman detail produk.
     * Menampilkan detail produk berdasarkan ID.
     * Jika user login, akan menghitung harga setelah diskon sesuai role user.
     *
     * @param int $id ID produk
     */
    public function detail(int $id)
    {
        $item = Item::findOrFail($id);

        $hargaSetelahDiskon = null;
        $diskonPersen = 0;

        if (Auth::check()) {
            foreach (Auth::user()->getRoleNames() as $role) {
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
