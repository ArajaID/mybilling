@extends('adminlte::page')

@section('title', 'Tagihan')

@section('content_header')
    <h1>Tagihan</h1>
@stop

@section('content')
    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>No Tagihan</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
                        <th>Jumlah Tagihan</th>
                        <th>Status Pembayaran</th>
                        <th>Deskripsi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($tagihan as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->kode_tagihan }}</td>
                            <td>{{ $data->pelanggan->kode_pelanggan }}</td>
                            <td>{{ $data->pelanggan->nama_pelanggan }}</td>
                            <td>@currency($data->jumlah_tagihan)</td>
                            <td>{!! $data->status_pembayaran == 'Belum Lunas'
                                ? "<badge class='badge badge-danger'>Belum Lunas</badge>"
                                : "<badge class='badge badge-success'>Lunas</badge>" !!}</td>
                            <td>{{ $data->deskripsi }}</td>
                            <td>
                                @if ($data->status_pembayaran == 'Belum Lunas')
                                    <a href="{{ route('tagihan.create') . '?kode_tagihan=' . $data->kode_tagihan }}"
                                        class="btn btn-primary btn-sm">Penerimaan Tagihan</a>
                                @endif
                            </td>
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
