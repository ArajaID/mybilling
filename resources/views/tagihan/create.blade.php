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

                        <input type="hidden" value="{{ $tagihan->deskripsi }}" name="deskripsi">
                        <input type="hidden" value="{{ $tagihan->kode_tagihan }}" name="kode_tagihan">

                        <div class="row">
                            <x-adminlte-input type="number" name="jumlah" label="Jumlah" fgroup-class="col-md-3"
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
