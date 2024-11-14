@extends('adminlte::page')

@section('title', 'Daftar Paket')

@section('content_header')
    <h1>Daftar Paket</h1>
@stop

@section('content')
    <a href={{ route('paket.create') }} class="btn btn-primary ">Tambah Paket</a>

    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Nama Paket</th>
                        <th>Kecepatan</th>
                    </tr>
                </thead>
                <tbody>

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
