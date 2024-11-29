@extends('adminlte::page')

@section('title', 'Daftar ODP')

@section('content_header')
    <h1>Daftar ODP</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('odp.create') }} class="btn btn-primary ">Tambah ODP</a>
    </div>

    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ODC</th>
                        <th>ODP</th>
                        <th>Lokasi</th>
                        <th>Port</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($odp as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->odc->odc }}</td>
                            <td>{{ $data->odp }}</td>
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
