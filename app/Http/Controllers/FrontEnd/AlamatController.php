<?php

namespace App\Http\Controllers\FrontEnd;

use App\Http\Controllers\Controller;
use App\Models\Alamat;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AlamatController extends Controller
{
    /**
     * Menampilkan daftar alamat milik user yang sedang login.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $alamatList = Alamat::where('user_id', Auth::id())->get();

        return view('pages.frontend.profile', compact('alamatList'));
    }

    /**
     * Menyimpan alamat baru ke database.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        // Validasi input
        $request->validate(
            [
                'nama_penerima'   => 'required|string|max:255',
                'provinsi'        => 'required|string',
                'kota'            => 'required|string',
                'alamat_lengkap'  => 'required|string',
                'no_telp'         => ['required', 'regex:/^08[0-9]{8,11}$/'],
            ],
            [
                'no_telp.regex' => 'Nomor telepon harus dimulai dengan 08 dan berisi 10-13 digit angka.',
            ]
        );

        // Simpan data alamat
        Alamat::create([
            'user_id'        => auth()->id(),
            'nama_penerima'  => $request->nama_penerima,
            'provinsi'       => $request->provinsi,
            'kota'           => $request->kota,
            'alamat_lengkap' => $request->alamat_lengkap,
            'no_telp'        => $request->no_telp,
        ]);

        return back()->with('success', 'Alamat berhasil ditambahkan.');
    }

    /**
     * Memperbarui data alamat berdasarkan ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function update(Request $request, $id)
    {
        // Validasi input
        $request->validate(
            [
                'nama_penerima'   => 'nullable|string|max:255',
                'provinsi'        => 'nullable|string',
                'kota'            => 'nullable|string',
                'alamat_lengkap'  => 'nullable|string',
                'no_telp'         => ['nullable', 'regex:/^08[0-9]{8,11}$/'],
            ],
            [
                'no_telp.regex' => 'Nomor telepon harus dimulai dengan 08 dan berisi 10-13 digit angka.',
            ]
        );

        // Update data alamat
        $alamat = Alamat::findOrFail($id);
        $alamat->update([
            'nama_penerima'  => $request->nama_penerima,
            'provinsi'       => $request->provinsi,
            'kota'           => $request->kota,
            'alamat_lengkap' => $request->alamat_lengkap,
            'no_telp'        => $request->no_telp,
        ]);

        return back()->with('success', 'Alamat berhasil diperbarui.');
    }

    /**
     * Menghapus alamat berdasarkan ID.
     *
     * @param  int  $id
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy($id)
    {
        $alamat = Alamat::where('id', $id)
            ->where('user_id', Auth::id())
            ->firstOrFail();

        $alamat->delete();

        return redirect()
            ->route('alamat.index')
            ->with('success', 'Alamat berhasil dihapus.');
    }
}
