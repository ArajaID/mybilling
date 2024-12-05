@extends('adminlte::page')

@section('title', 'Detail Pelanggan')

@section('content_header')
    <h1>Detail Pelanggan </h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Kode Pelanggan ({{ $pelanggan->kode_pelanggan }})</h3>
                </div>
                <div class="card-body">
                    <strong><i class="mr-1 fas fa-user"></i> Nama Pelanggan</strong>
                    <p class="text-muted">
                        {{ $pelanggan->nama_pelanggan }}
                    </p>

                    <hr>

                    <strong><i class="mr-1 fas fa-wifi"></i> Paket Internet</strong>
                    <p class="text-muted">
                        {{ $pelanggan->paket->nama_paket }}
                    </p>

                    <hr>

                    <strong><i class="mr-1 fas fa-envelope"></i> Email</strong>
                    <p class="text-muted">
                        {{ $pelanggan->email ? $pelanggan->email : 'Data tidak ada' }}
                    </p>

                    <hr>

                    <strong><i class="mr-1 fas fa-phone"></i> No HP</strong>
                    <p class="text-muted">
                        {{ $pelanggan->no_telepon ? $pelanggan->no_telepon : 'Data tidak ada' }}
                    </p>

                    <hr>

                    <strong><i class="mr-1 fas fa-map-marker-alt"></i> Alamat ODC dan ODP</strong>
                    <p class="text-muted">
                        {{ $pelanggan->odpData->odc->odc_induk . '/' . $pelanggan->odpData->odc->odc . '/' . $pelanggan->odpData->odp }}
                    </p>

                    <hr>

                    <strong><i class="mr-1 fas fa-key"></i> Akun PPPoE</strong>
                    <p class="text-muted">
                        User PPPoE : {{ $pelanggan->user_pppoe }} <br>
                        Password PPPoE : {{ $pelanggan->password_pppoe }}
                    </p>

                    <hr>

                    <strong><i class="mr-1 fas fa-percent"></i> Promo</strong>

                    <p class="text-muted">

                        @if ($promoActive)
                            Kode Promo : {{ $promoActive->kode_promo }} <br>
                            Promo : {{ $promoActive->nama_promo }}
                        @else
                            Tanpa Promo
                        @endif
                    </p>

                    <hr>
                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card">
                <div class="p-2 card-header">
                    <ul class="nav nav-pills">
                        <li class="nav-item"><a class="nav-link active" href="#history" data-toggle="tab">History
                                Pembayaran</a></li>

                    </ul>
                </div>
                <div class="p-0 card-body table-responsive">
                    <div class="tab-content">
                        <div class="active tab-pane" id="history">
                            <table class="table table-hover text-nowrap">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">No</th>
                                        <th>Kode Tagihan</th>
                                        <th>Tanggal Tagihan</th>
                                        <th>Total</th>
                                        <th>Deskripsi</th>
                                        <th>Status Pembayaran</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($historyTagihan as $data)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->kode_tagihan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_tagihan)->format('d-m-Y') }}</td>
                                            <td>@currency($data->jumlah_tagihan)</td>
                                            <td>{{ $data->deskripsi }}</td>
                                            <td>{!! $data->status_pembayaran == 'LUNAS'
                                                ? "<span class='badge badge-success'>Lunas</span>"
                                                : "<span class='badge badge-danger'>Belum Lunas</span>" !!}
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>

                            <div class="clearfix card-footer">
                                <ul class="float-right m-0 pagination pagination-sm">
                                    {{ $historyTagihan->links() }}
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
