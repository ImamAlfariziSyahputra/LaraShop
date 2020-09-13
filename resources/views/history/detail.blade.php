@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="{{ url('history') }}" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
        </div>
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('history') }}">Riwayat Pembayaran</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Detail Pemesanan</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <div class="btn btn-success">Checkout Berhasil!</div>
                    <h5>Selanjutnya melakukan pembayaran dengan transfer ke <br> Rekening <strong>Bank BRI : XXXX-XXXX-XXXX</strong> <br> Dengan Nominal : <strong>Rp. {{ number_format($pesanan->kode + $pesanan->jumlah_harga,0,',','.') }} </strong></h5>
                </div>
            </div>
            <div class="card mt-2">
                <div class="card-body">
                    <h3><i class="fa fa-shopping-cart" aria-hidden="true"></i> Detail Pemesanan</h3>
                    @if (!empty($pesanan))
                    <p align="right">Tanggal Pesan : {{ $pesanan->tanggal }}</p>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Gambar</th>
                                <th>Nama Barang</th>
                                <th>Jumlah</th>
                                <th>Harga</th>
                                <th>Total Harga</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1 ?>
                            @foreach ($pesanan_details as $pesanan_detail)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>
                                    <img src="{{ url('uploads') }}/{{ $pesanan_detail->barang->gambar }}" width="100">
                                </td>
                                <td>{{ $pesanan_detail->barang->nama_barang }}</td>
                                <td>{{ $pesanan_detail->jumlah }}</td>
                                <td align="right">
                                    Rp. {{ number_format($pesanan_detail->barang->harga,0,',','.') }}
                                </td>
                                <td align="right">
                                    Rp. {{ number_format($pesanan_detail->jumlah_harga,0,',','.') }}
                                </td>
                            </tr>
                            @endforeach
                            <tr>
                                <td colspan="5" align="right">
                                    <strong>Total Harga :</strong>
                                </td>
                                <td align="right">
                                    <strong>Rp. {{ number_format($pesanan->jumlah_harga,0,',','.') }} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right">
                                    <strong>Kode Unik :</strong>
                                </td>
                                <td align="right">
                                    <strong>Rp. {{ $pesanan->kode }} </strong>
                                </td>
                            </tr>
                            <tr>
                                <td colspan="5" align="right">
                                    <strong>Kode Unik :</strong>
                                </td>
                                <td align="right">
                                    <strong>Rp. {{ number_format($pesanan->kode + $pesanan->jumlah_harga,0,',','.') }} </strong>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
