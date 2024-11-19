@extends('adminlte::page')

@section('title', 'Tambah Pengeluaran')

@section('content_header')
    <h1>Tambah Pengeluaran</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('paket.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input name="nama_paket" label="Nama Paket" fgroup-class="col-md-6"
                                value="{{ old('nama_paket') }}" />
                            <x-adminlte-input type="number" name="harga" label="Harga" fgroup-class="col-md-3"
                                value="{{ old('harga') }}" />
                            <x-adminlte-select name="bandwidth" label="Bandwidth" fgroup-class="col-md-3">
                                <option value="0">Pilih Bandwidth</option>

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
