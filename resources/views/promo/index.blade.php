@extends('adminlte::page')

@section('title', 'Daftar Promo')

@section('content_header')
    <h1>Daftar Promo</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('promo.create') }} class="btn btn-primary ">Tambah Promo</a>
    </div>

    <div class="card">
        <div class="p-0 card-body table-responsive" style="height: auto;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Promo</th>
                        <th>Nama Promo</th>
                        <th>Tanggal Mulai</th>
                        <th>Tanggal Berakhir</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($dataPromo as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->kode_promo }}</td>
                            <td>{{ $data->nama_promo }}</td>
                            <td>{{ $data->tanggal_mulai }}</td>
                            <td>{{ $data->tanggal_berakhir }}</td>
                            <td></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@stop
