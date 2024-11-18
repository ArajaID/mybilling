@extends('adminlte::page')

@section('title', 'Tambah Promo')

@section('content_header')
    <h1>Tambah Promo</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('promo.store') }}" method="post">
                    @csrf
                    <div class="card-body">
                        <input type="hidden" value="{{ $kodePromo }}" name="kode_promo">
                        <div class="row">
                            <x-adminlte-input name="nama_promo" label="Nama Promo" fgroup-class="col-md-4"
                                value="{{ old('nama_promo') }}" />
                            <x-adminlte-input type="number" name="diskon" label="Diskon" fgroup-class="col-md-2"
                                value="{{ old('diskon') }}" />

                            <x-adminlte-select name="tambahan_speed" label="Upgrade Speed" fgroup-class="col-md-2">
                                <option value="">Pilih Speed</option>

                            </x-adminlte-select>

                            <x-adminlte-input type="date" name="tanggal_mulai" label="Tanggal Mulai"
                                fgroup-class="col-md-2" value="{{ old('tanggal_mulai') }}" />

                            <x-adminlte-input type="date" name="tanggal_berakhir" label="Tanggal Berakhir"
                                fgroup-class="col-md-2" value="{{ old('tanggal_berakhir') }}" />
                        </div>

                        <div class="row">
                            <x-adminlte-textarea name="deskripsi" label="Deskripsi" fgroup-class="col-md-6" />
                            <x-adminlte-textarea name="syarat_ketentuan" label="Syarat & Ketentuan"
                                fgroup-class="col-md-6" />

                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('promo.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
