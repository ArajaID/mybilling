@extends('adminlte::page')

@section('title', 'Pengeluaran')

@section('content_header')
    <h1>Pengeluaran</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('pengeluaran.create') }} class="btn btn-primary ">Tambah Pengeluaran</a>
    </div>

    <div class="card">
        <div class="p-0 card-body table-responsive" style="height: auto;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Sumber</th>
                        <th>Kategori</th>
                        <th>Metode Pembayaran</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pengeluaran as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->sumber }}</td>
                            <td>{{ $data->kategori }}</td>
                            <td>{{ $data->metode_pembayaran }}</td>
                            <td>@currency($data->kredit)</td>
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
