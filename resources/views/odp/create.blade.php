@extends('adminlte::page')

@section('title', 'Tambah ODP')

@section('content_header')
    <h1>Tambah ODP</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('odp.store') }}" method="post">
                    @csrf
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-select name="odc_id" label="ODC" fgroup-class="col-md-2">
                                <option value="0">Pilih ODC</option>
                                @foreach ($odc as $item)
                                    <option value="{{ $item->id }}">{{ $item->odc }}</option>
                                @endforeach
                            </x-adminlte-select>
                            <x-adminlte-input name="odp" label="odp" fgroup-class="col-md-2"
                                value="{{ old('odp') }}" />
                            <x-adminlte-input name="lokasi" label="Lokasi" fgroup-class="col-md-7"
                                value="{{ old('lokasi') }}" />
                            <x-adminlte-input name="port" label="Port" fgroup-class="col-md-1"
                                value="{{ old('port') }}" />
                        </div>


                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('odp.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
