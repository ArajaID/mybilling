@extends('adminlte::page')

@section('title', 'Tambah Pemasukan')

@section('content_header')
    <h1>Tambah Pemasukan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('pemasukan.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input type="date" name="tanggal" label="Tanggal Pemasukan" fgroup-class="col-md-2"
                                value="{{ now()->format('Y-m-d') }}" />

                            <x-adminlte-input type="number" name="debit" label="Jumlah" fgroup-class="col-md-2"
                                value="{{ old('debit') }}" />
                            <x-adminlte-input name="kategori" label="Kategori" fgroup-class="col-md-2"
                                value="{{ old('kategori') }}" />
                            <x-adminlte-select name="metode_pembayaran" label="Metode Pembayaran" fgroup-class="col-md-2">
                                <option value="0">Pilih Metode Pembayaran</option>
                                <option value="QRIS">QRIS</option>
                                <option value="Cash">Cash</option>
                                <option value="Transfer">Transfer</option>
                            </x-adminlte-select>
                            <x-adminlte-textarea name="deskripsi" label="Deskripsi" fgroup-class="col-md-4" />
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('pemasukan.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
