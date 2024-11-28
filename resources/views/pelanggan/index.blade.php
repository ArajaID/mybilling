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
                <div class="input-group input-group-sm" style="width: 150px;">
                    <input type="text" name="table_search" class="float-right form-control" placeholder="Search">

                    <div class="input-group-append">
                        <button type="submit" class="btn btn-default">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-0 card-body table-responsive" style="height: auto;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kode Pelanggan</th>
                        <th>Nama Pelanggan</th>
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
                            <td>{{ $data->paket->nama_paket }}</td>
                            <td>{!! $data->aktivasi_layanan
                                ? '<badge class="badge badge-success">Sudah Diaktivasi</badge>'
                                : '<badge class="badge badge-danger">Belum Diaktivasi</badge>' !!}</td>
                            <td>
                                <a href="{{ route('pelanggan.show', $data->id) }}" class="btn btn-warning btn-sm"><i
                                        class="fas fa-eye"></i> Detail</a>
                                <a href="{{ route('aktivasi.create') . '?kode_pelanggan=' . $data->kode_pelanggan }}"
                                    class="btn btn-info btn-sm {{ $data->aktivasi_layanan ? 'disabled' : '' }}"><i
                                        class="fas fa-key"></i> Aktivasi</a>
                                @if ($data->aktivasi_layanan)
                                    <a href="" class="btn btn-dark btn-sm"><i class="fas fa-print"></i> Cetak</a>
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
    </div>
@stop

@section('css')
    {{-- Add here extra stylesheets --}}
    {{-- <link rel="stylesheet" href="/css/admin_custom.css"> --}}
@stop

@section('js')

@stop
