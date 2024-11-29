@extends('adminlte::page')

@section('title', 'Daftar ODC')

@section('content_header')
    <h1>Daftar ODC</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('odc.create') }} class="btn btn-primary ">Tambah ODC</a>
    </div>

    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ODC Induk</th>
                        <th>ODC</th>
                        <th>Lokasi</th>
                        <th>Port</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($odc as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->odc_induk }}</td>
                            <td>{{ $data->odc }}</td>
                            <td>{{ $data->lokasi }}</td>
                            <td>{{ $data->port }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
