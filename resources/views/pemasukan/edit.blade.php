@extends('adminlte::page')

@section('title', 'Edit Pemasukan')

@section('content_header')
    <h1>Edit Pemasukan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('pemasukan.update', $pemasukan->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input type="date" name="tanggal" label="Tanggal Pemasukan" fgroup-class="col-md-2"
                                value="{{ old('tanggal', $pemasukan->tanggal) }}" />
                            <x-adminlte-input type="number" name="debit" label="Jumlah" fgroup-class="col-md-2"
                                value="{{ old('debit', $pemasukan->debit) }}" />
                            <x-adminlte-input name="kategori" label="Kategori" fgroup-class="col-md-2"
                                value="{{ old('kategori', $pemasukan->kategori) }}" />
                            <x-adminlte-select name="metode_pembayaran" label="Metode Pembayaran" fgroup-class="col-md-2">
                                <option value="0">Pilih Metode Pembayaran</option>
                                <option value="QRIS" {{ $pemasukan->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>
                                    QRIS</option>
                                <option value="Cash" {{ $pemasukan->metode_pembayaran == 'Cash' ? 'selected' : '' }}>
                                    Cash</option>
                                <option value="Transfer"
                                    {{ $pemasukan->metode_pembayaran == 'Transfer' ? 'selected' : '' }}>
                                    Transfer</option>
                            </x-adminlte-select>
                            <x-adminlte-textarea name="deskripsi" label="Deskripsi" fgroup-class="col-md-4"
                                value="{{ old('deskripsi', $pemasukan->deskripsi) }}">
                                {{ $pemasukan->deskripsi }}
                            </x-adminlte-textarea>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Edit</button>
                        <a href="{{ route('pemasukan.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
