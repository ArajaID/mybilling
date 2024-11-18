@extends('adminlte::page')

@section('title', 'Aktivasi Layanan')

@section('content_header')
    <h1>Aktivasi Layanan</h1>
@stop

@section('plugins.Select2', true)

@section('content')
    <div class="mb-4 container-fluid">

        <form action="" method="get">
            <div class="row">
                <div class="col"></div>
                <div class="text-center col-md-5">
                    <input type="text" name="kode_pelanggan" class="form-control"
                        value="{{ isset($kodePelanggan) ? $kodePelanggan : '' }}" placeholder="Masukkan Kode Pelanggan">
                    <button type="submit" class="mt-2 btn btn-outline-secondary">Cari</button>
                </div>
                <div class="col"></div>
            </div>
        </form>

        @if ($dataPelanggan)
            <div class="mt-2 row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="form-row">
                                <x-adminlte-input name="nama_pelanggan" label="Nama Pelanggan" fgroup-class="col-md-4"
                                    value="{{ $dataPelanggan->nama_pelanggan }}" disabled />

                                <x-adminlte-input name="id_paket" label="Paket Internet" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->paket->nama_paket }}" disabled />

                                <x-adminlte-input name="email" label="Email" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->email }}" disabled />

                                <x-adminlte-input name="no_telepon" label="No Telepon" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->no_telepon }}" disabled />

                                <x-adminlte-input name="blok" label="Blok" fgroup-class="col-md-1"
                                    value="{{ $dataPelanggan->blok }}" disabled />

                                <x-adminlte-input name="rt" label="RT" fgroup-class="col-md-1"
                                    value="{{ $dataPelanggan->rt }}" disabled />
                            </div>

                            <div class="form-row">
                                <x-adminlte-input name="area" label="Area" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->area }}" disabled />

                                <x-adminlte-input name="odc_odp" label="Alamat ODC dan ODP" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->odc_odp }}" disabled />

                                <x-adminlte-input name="user_pppoe" label="Username PPPoE" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->user_pppoe }}" disabled />

                                <x-adminlte-input name="password_pppoe" label="Password PPPoE" fgroup-class="col-md-2"
                                    value="{{ $dataPelanggan->password_pppoe }}" disabled />

                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <form action="" method="POST">
                @csrf

                <div class="row">
                    <div class="col-lg-12">
                        <div class="form-group">
                            <button class="btn btn-primary btn-sm">Aktivasi <i class="fas fa-key"></i></button>
                            <a href="" class="btn btn-info btn-sm"><i class="fas fa-caret-left"></i> Kembali</a>
                        </div>
                    </div>

                    <div class="col-md-12">

                        <input type="hidden" value="{{ $dataPelanggan->id }}" name="id_pelanggan">

                        <div class="card">
                            <div class="card-body">
                                <div class="form-row">

                                    <x-adminlte-select2 name="id_promo" label="Promo" fgroup-class="col-md-6" required>
                                        <option value="" selected>Pilih Promo</option>
                                        @foreach ($daftarPromo as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->kode_promo . ' - ' . $item->nama_promo }}</option>
                                        @endforeach
                                    </x-adminlte-select2>

                                    <x-adminlte-input type="date" name="tanggal_klaim" label="Tanggal Klaim Promo"
                                        fgroup-class="col-md-2" value="{{ now()->format('Y-m-d') }}" required />

                                    <x-adminlte-input type="number" name="berlaku_bulan" label="Berlaku Bulan"
                                        fgroup-class="col-md-2" value="{{ old('berlaku_bulan') }}" />

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </form>
        @endif
    </div>
@stop
