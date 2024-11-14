@extends('adminlte::page')

@section('title', 'Daftar Paket')

@section('content_header')
    <h1>Daftar Paket</h1>
@stop

@section('content')
    <div class="mb-2">

        <a href={{ route('paket.create') }} class="btn btn-primary ">Tambah Paket</a>
    </div>

    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Paket</th>
                        <th>Kecepatan</th>
                        <th>Harga</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($paketInet as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
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
