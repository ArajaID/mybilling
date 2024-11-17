@extends('adminlte::page')

@section('title', 'Tambah Pelanggan')

@section('content_header')
    <h1>Tambah Pelanggan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('pelanggan.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <h5 class="font-weight-bold">Kode Pelanggan : {{ $kodePelanggan }}</h5>
                        <input type="hidden" value="{{ $kodePelanggan }}" name="kode_pelanggan">

                        <div class="row">
                            <x-adminlte-input name="nama_pelanggan" label="Nama Pelanggan" fgroup-class="col-md-5"
                                value="{{ old('nama_pelanggan') }}" />
                            <x-adminlte-input type="email" name="email" label="Email" fgroup-class="col-md-2"
                                value="{{ old('email') }}" />
                            <x-adminlte-input type="number" name="no_telepon" label="No Hp" fgroup-class="col-md-2"
                                value="{{ old('no_telepon') }}" />
                            <x-adminlte-input name="blok" label="Blok" fgroup-class="col-md-1"
                                value="{{ old('blok') }}" />
                            <x-adminlte-select name="rt" label="RT" fgroup-class="col-md-2">
                                <option value="">Pilih RT</option>
                                <option value="001">001</option>
                                <option value="002">002</option>
                            </x-adminlte-select>
                        </div>

                        <div class="row">
                            <x-adminlte-select name="area" label="Area" fgroup-class="col-md-2">
                                <option value="">Pilih Area</option>
                                <option value="perumahan">Perumahan</option>
                                <option value="diluar_perumahan">Diluar Perumahan</option>
                            </x-adminlte-select>
                            <x-adminlte-input name="odc_odp" label="Alamat ODC dan ODP" fgroup-class="col-md-3"
                                value="{{ old('odc_odp') }}" />
                            <x-adminlte-select name="id_paket" label="Paket Internet" fgroup-class="col-md-3">
                                <option value="">Pilih Paket</option>
                                @foreach ($paketInet as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_paket }}</option>
                                @endforeach
                            </x-adminlte-select>

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('paket.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
