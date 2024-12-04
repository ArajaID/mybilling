@extends('adminlte::page')

@section('title', 'Terima Pembayaran')

@section('content_header')
    <h1>Terima Pembayaran</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('tagihan.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <input type="hidden" name="kode_pelanggan" value="{{ $tagihan->pelanggan->kode_pelanggan }}">

                            <x-adminlte-input name="kode_tagihan" label="No Tagihan" fgroup-class="col-md-2"
                                value="{{ old('kode_tagihan', $tagihan->kode_tagihan) }}" readonly />

                            <x-adminlte-input name="nama_pelanggan" label="Nama Pelanggan" fgroup-class="col-md-2"
                                value="{{ old('pelanggan', $tagihan->pelanggan->nama_pelanggan) }}" readonly />

                            <x-adminlte-input name="deskripsi" label="Deskripsi" fgroup-class="col-md-2"
                                value="{{ old('deskripsi', $tagihan->deskripsi) }}" readonly />

                            <x-adminlte-input type="date" name="tanggal" label="Tanggal Penerimaan"
                                fgroup-class="col-md-2" value="{{ now()->format('Y-m-d') }}" />

                            <x-adminlte-input type="number" name="jumlah" label="Jumlah" fgroup-class="col-md-2"
                                value="{{ old('jumlah', $tagihan->jumlah_tagihan) }}" />

                            <x-adminlte-select name="metode_pembayaran" label="Metode Pembayaran" fgroup-class="col-md-2">
                                <option value="0">Pilih Metode Pembayaran</option>
                                <option value="QRIS" selected>QRIS</option>
                                <option value="Cash">Cash</option>
                            </x-adminlte-select>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('tagihan.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
