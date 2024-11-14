@extends('adminlte::page')

@section('title', 'Pelanggan')

@section('content_header')
    <h1>Pelanggan</h1>
@stop

@section('content')
    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>Username PPPoE</th>
                        <th>Password PPPoE</th>
                        <th>Caller ID</th>
                        <th>Paket</th>
                        <th>Status Bayar</th>
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
