@extends('adminlte::page')

@section('title', 'Edit Pengeluaran')

@section('content_header')
    <h1>Edit Pengeluaran</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('pengeluaran.update', $pengeluaran->id) }}" method="post">
                    @csrf
                    @method('put')
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input type="date" name="tanggal" label="Tanggal Pengeluaran" fgroup-class="col-md-2"
                                value="{{ old('tanggal', $pengeluaran->tanggal) }}" />
                            <x-adminlte-input type="number" name="kredit" label="Jumlah" fgroup-class="col-md-2"
                                value="{{ old('kredit', $pengeluaran->kredit) }}" />
                            <x-adminlte-input name="kategori" label="Kategori" fgroup-class="col-md-2"
                                value="{{ old('kategori', $pengeluaran->kategori) }}" />
                            <x-adminlte-select name="metode_pembayaran" label="Metode Pembayaran" fgroup-class="col-md-2">
                                <option value="0">Pilih Metode Pembayaran</option>
                                <option value="QRIS" {{ $pengeluaran->metode_pembayaran == 'QRIS' ? 'selected' : '' }}>
                                    QRIS</option>
                                <option value="Cash" {{ $pengeluaran->metode_pembayaran == 'Cash' ? 'selected' : '' }}>
                                    Cash</option>
                            </x-adminlte-select>
                            <x-adminlte-textarea name="deskripsi" label="Deskripsi" fgroup-class="col-md-4"
                                value="{{ old('deskripsi', $pengeluaran->deskripsi) }}">
                                {{ $pengeluaran->deskripsi }}
                            </x-adminlte-textarea>
                        </div>

                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-info">Edit</button>
                        <a href="{{ route('pengeluaran.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
