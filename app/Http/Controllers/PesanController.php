<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Barang;
use App\Pesanan;
use App\PesananDetail;
use App\User;

use Alert;
use Auth;
use Carbon\Carbon;

class PesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
    }

    public function pesan(Request $request, $id)
    {
        $barang = Barang::where('id', $id)->first();
        $tanggal = Carbon::now();

        // validasi apakah melebihi stok
        if($request->jumlah_pesan > $barang->stok)
        {
            return redirect('pesan', $id);
        }
        // cek validasi
        $cek_pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first(); // Pesanan Yang Statusnya 0

        // Simpan ke database pesanan
        if (empty($cek_pesanan)) {

            $pesanan = new Pesanan;
            $pesanan->user_id = Auth::user()->id;
            $pesanan->tanggal = $tanggal;
            $pesanan->status = 0;
            $pesanan->kode = mt_rand(100, 999);
            $pesanan->jumlah_harga = 0;
            $pesanan->save();
        }
        // simpan ke database pesanan_detail
        $pesanan_baru = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();

        // cek pesanan detail
        $cek_pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();

        if (empty($cek_pesanan_detail)) {
            $pesanan_detail = new PesananDetail();
            $pesanan_detail->barang_id = $barang->id;
            $pesanan_detail->pesanan_id = $pesanan_baru->id;
            $pesanan_detail->jumlah = $request->jumlah_pesan;
            $pesanan_detail->jumlah_harga = $barang->harga*$request->jumlah_pesan;
            $pesanan_detail->save();
        } else {
            $pesanan_detail = PesananDetail::where('barang_id', $barang->id)->where('pesanan_id', $pesanan_baru->id)->first();

            $pesanan_detail->jumlah += $request->jumlah_pesan;

            // harga sekarang
            $harga_pesanan_detail_baru = $barang->harga * $request->jumlah_pesan;
            $pesanan_detail->jumlah_harga += $harga_pesanan_detail_baru;
            $pesanan_detail->update();
        }

        // jumlah total
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $pesanan->jumlah_harga += $barang->harga * $request->jumlah_pesan;
        $pesanan->update();

        alert()->success('Pesanan Berhasil Masuk Keranjang', 'Optional Title');
        return redirect('home');
    }

    public function checkout()
    {
        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $pesanan_details = [];
        if(!empty($pesanan))
        {
            $pesanan_details = PesananDetail::where('pesanan_id', $pesanan->id)->get();
        }

        return view('pesan.checkout', compact('pesanan', 'pesanan_details'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $barang = Barang::where('id', $id)->first();

        return view('pesan.index', compact('barang'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $pesanan_detail = PesananDetail::where('id', $id)->first();
        $pesanan = Pesanan::where('id', $pesanan_detail->pesanan_id)->first();

        $pesanan->jumlah_harga -= $pesanan_detail->jumlah_harga;
        $pesanan->update();

        $pesanan_detail->delete();

        alert()->success('Pesanan Berhasil Dihapus', 'Error');
        return redirect('checkout');
    }

    public function confirm()
    {
        $user = User::where('id', Auth::user()->id)->first();

        if(empty($user->alamat))
        {
            alert()->error('Identitas Mohon Dilengkapi', 'Error');
            return redirect('profile');
        }

        if(empty($user->nohp))
        {
            alert()->error('Identitas Mohon Dilengkapi', 'Hapus');
            return redirect('profile');
        }

        $pesanan = Pesanan::where('user_id', Auth::user()->id)->where('status', 0)->first();
        $pesanan_id = $pesanan->id;

        $pesanan->status = 1;
        $pesanan->update();

        $pesanan_details = PesananDetail::where('pesanan_id', $pesanan_id)->get();
        foreach ($pesanan_details as $pesanan_detail) {
            $barang = Barang::where('id', $pesanan_detail->barang_id)->first();
            $barang->stok -= $pesanan_detail->jumlah;
            $barang->update();
        }

        alert()->success('Pesanan Berhasil Checkout, Silahkan Lakukan Pembayaran', 'Success');
        return redirect('history/'. $pesanan_id);
    }
}
