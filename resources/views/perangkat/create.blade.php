@extends('adminlte::page')

@section('title', 'Tambah Perangkat')

@section('content_header')
    <h1>Tambah Perangkat</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('perangkat.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input name="nama_perangkat" label="Nama Perangkat" fgroup-class="col-md-4"
                                value="{{ old('nama_perangkat') }}" />
                            <x-adminlte-input name="merek" label="Merek" fgroup-class="col-md-2"
                                value="{{ old('merek') }}" />
                            <x-adminlte-input name="tipe" label="Tipe" fgroup-class="col-md-2"
                                value="{{ old('tipe') }}" />
                            <x-adminlte-input name="sn" label="Serial Number" fgroup-class="col-md-4"
                                value="{{ old('sn') }}" />


                        </div>

                        <div class="row">
                            <x-adminlte-textarea name="catatan" label="Catatan" fgroup-class="col-md-12"
                                value="{{ old('catatan') }}" />
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('odc.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
