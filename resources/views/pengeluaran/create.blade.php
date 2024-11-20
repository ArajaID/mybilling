@extends('adminlte::page')

@section('title', 'Tambah Pengeluaran')

@section('content_header')
    <h1>Tambah Pengeluaran</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('pengeluaran.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input type="number" name="kredit" label="Jumlah" fgroup-class="col-md-2"
                                value="{{ old('kredit') }}" />
                            <x-adminlte-input name="kategori" label="Kategori" fgroup-class="col-md-2"
                                value="{{ old('kategori') }}" />
                            <x-adminlte-select name="metode_pembayaran" label="Metode Pembayaran" fgroup-class="col-md-2">
                                <option value="0">Pilih Metode Pembayaran</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Cash">Cash</option>
                            </x-adminlte-select>
                            <x-adminlte-textarea name="deskripsi" label="Deskripsi" fgroup-class="col-md-6" />
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
