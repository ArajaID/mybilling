@extends('adminlte::page')

@section('title', 'Pelanggan')

@section('content_header')
    <h1>Pelanggan</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('pelanggan.create') }} class="btn btn-primary ">Tambah Pelanggan</a>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Daftar Pelanggan</h3>

            <div class="card-tools">
                <form action="{{ route('pelanggan.index') }}" method="get">
                    <div class="input-group input-group-sm" style="width: 280px;">
                        <input type="text" name="keyword" class="float-right form-control"
                            placeholder="Pencarian kode/nama/blok pelanggan" value="{{ isset($search) ? $search : '' }}">

                        <div class="input-group-append">
                            <button type="submit" class="btn btn-default">
                                <i class="fas fa-search"></i>
                            </button>
                        </div>
                    </div>
                </form>
            </div>

        </div>

        @if (!$pelanggan->isEmpty())
            <div class="p-0 card-body table-responsive" style="height: auto;">
                <table class="table table-head-fixed text-nowrap">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Kode Pelanggan</th>
                            <th>Nama Pelanggan</th>
                            <th>Area</th>
                            <th>Paket</th>
                            <th>Status Aktivasi</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($pelanggan as $data)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $data->kode_pelanggan }}</td>
                                <td>{{ $data->nama_pelanggan }}</td>
                                <td>{{ Str::title($data->area) . ' - ' . Str::upper($data->blok) }}</td>
                                <td>{{ $data->paket->nama_paket }}</td>
                                <td>{!! $data->aktivasi_layanan
                                    ? '<badge class="badge badge-success">Sudah Diaktivasi</badge>'
                                    : '<badge class="badge badge-danger">Belum Diaktivasi</badge>' !!}</td>
                                <td>
                                    <a href="{{ route('pelanggan.show', $data->id) }}" class="btn btn-warning btn-sm"><i
                                            class="fas fa-eye"></i> Detail</a>
                                    @if (!$data->aktivasi_layanan)
                                        <a href="{{ route('aktivasi.create') . '?kode_pelanggan=' . $data->kode_pelanggan }}"
                                            class="btn btn-info btn-sm"><i class="fas fa-key"></i> Aktivasi</a>
                                    @endif
                                    @if ($data->aktivasi_layanan)
                                        <a href="#" class="btn btn-dark btn-sm"><i class="fas fa-print"></i> Cetak</a>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <div class="clearfix card-footer">
                    <ul class="float-right m-0 pagination pagination-sm">
                        {{ $pelanggan->links() }}
                    </ul>
                </div>
            </div>
        @else
            <x-adminlte-alert class="text-center bg-warning" icon="fas fa-lg fa-exclamation-triangle" title="Oops"
                dismissable>
                Mohon maaf data <b>{{ isset($search) ? $search : '' }}</b> tidak ditemukan!
            </x-adminlte-alert>
        @endif
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
