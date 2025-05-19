@extends('adminlte::page')

@section('title', 'Tagihan')

@section('content_header')
    <h1>Tagihan</h1>
@stop

@section('content')
    @if (!$tagihan->isEmpty())
        <div class="card">
            <div class="p-0 card-body table-responsive" style="height: auto;">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>No Tagihan</th>
                            <th>Tanggal</th>
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
                                <td>{{ \Carbon\Carbon::parse($data->tanggal_tagihan)->format('d-m-Y') }}</td>
                                <td>{{ $data->pelanggan->kode_pelanggan }}</td>
                                <td>{{ $data->pelanggan->nama_pelanggan }}</td>
                                <td class="text-right">@currency($data->jumlah_tagihan)</td>
                                <td>{!! $data->status_pembayaran == 'BELUM-LUNAS'
                                    ? "<badge class='badge badge-danger'>Belum Lunas</badge>"
                                    : "<badge class='badge badge-success'>Lunas</badge>" !!}</td>
                                <td>{{ $data->deskripsi }}</td>
                                <td>
                                    @if ($data->status_pembayaran == 'BELUM-LUNAS')
                                        <a href="{{ route('tagihan.create') . '?kode_tagihan=' . $data->kode_tagihan }}"
                                            class="btn btn-primary btn-sm">Penerimaan Tagihan</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                        <tr>
                            <td colspan="5" class="text-center">Total Tagihan</td>
                            <td class="text-right">
                                @currency($totalTagihan)
                            </td>
                            <td colspan="3"></td>
                        </tr>

                    </tbody>
                </table>
            </div>
        </div>
    @else
        <x-adminlte-alert class="text-center bg-warning" icon="fas fa-lg fa-exclamation-triangle" title="Oops"
            dismissable>
            Mohon maaf belum ada tagihan aktif!
        </x-adminlte-alert>
    @endif
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
