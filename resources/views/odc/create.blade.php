@extends('adminlte::page')

@section('title', 'Tambah ODC')

@section('content_header')
    <h1>Tambah ODC</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('odc.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input name="odc_induk" label="ODC Induk" fgroup-class="col-md-2"
                                value="{{ old('odc_induk') }}" />
                            <x-adminlte-input name="odc" label="ODC" fgroup-class="col-md-2"
                                value="{{ old('odc') }}" />
                            <x-adminlte-input name="lokasi" label="Lokasi" fgroup-class="col-md-7"
                                value="{{ old('lokasi') }}" />
                            <x-adminlte-input name="port" label="Port" fgroup-class="col-md-1"
                                value="{{ old('port') }}" />
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
