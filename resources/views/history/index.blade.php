@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mb-3">
            <a href="{{ url('home') }}" class="btn btn-primary"><i class="fa fa-arrow-left" aria-hidden="true"></i> Kembali</a>
        </div>
        <div class="col-md-12">
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('home') }}">Home</a></li>
                    <li class="breadcrumb-item active" aria-current="page">Riwayat Pemesanan</li>
                </ol>
            </nav>
        </div>
        <div class="col-md-12">
            <div class="card">
                <div class="card-body">
                    <h3><i class="fa fa-history" aria-hidden="true"></i> Checkout</h3>
                    <table class="table table-stripped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Tanggal</th>
                                <th>Status</th>
                                <th>Jumlah Harga</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $no = 1 ?>
                            @foreach ($pesanans as $pesanan)
                            <tr>
                                <td>{{ $no++ }}</td>
                                <td>{{ $pesanan->tanggal }}</td>
                                <td>
                                    @if ($pesanan->status == 1)
                                        Sudah Pesan & Belum Bayar
                                    @else
                                        Sudah Dibayar
                                    @endif
                                </td>
                                <td>Rp. {{ number_format($pesanan->jumlah_harga+$pesanan->kode,0,',','.') }}</td>
                                <td>
                                    <a href="{{ url('history') }}/{{ $pesanan->id }}" class="btn btn-sm btn-success">
                                        <i class="fa fa-info" aria-hidden="true"> Detail</i>
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
