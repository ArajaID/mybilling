@extends('adminlte::page')

@section('title', 'Laporan Promo Pelanggan')

@section('content_header')
    <h1>Laporan Promo Pelanggan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">

                        <a href="" target="_blank" class="btn btn-info btn-sm disabled"><i class="fas fa-download"></i>
                            PDF</a>

                    </h3>
                </div>
                <div class="p-0 card-body table-responsive" style="height: auto;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Kode Pelanggan</th>
                                <th>Nama Pelanggan</th>
                                <th>Tanggal Klaim</th>
                                <th>Nama Promo</th>
                                <th>Tanggal Berakhir Promo</th>
                                <th>Status Pelanggan</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $data)
                                @foreach ($data->promo as $item)
                                    @if ($item->id != 1)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $data->kode_pelanggan }}</td>
                                            <td>{{ $data->nama_pelanggan }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal_aktivasi)->isoFormat('D MMM Y') }}
                                            </td>
                                            <td>{{ $item->nama_promo . ' ' . $item->tanggal_berakhir_promo }}</td>
                                            <td>
                                                {{ \Carbon\Carbon::parse($item->pivot->tanggal_berakhir_promo)->isoFormat('D MMM Y') }}
                                            </td>
                                            <td>
                                                {!! $data->is_active
                                                    ? "<span class='badge badge-success'>Aktif</span>"
                                                    : "<span class='badge badge-danger'>Tidak Aktif</span>" !!}
                                            </td>
                                        </tr>
                                    @endif
                                @endforeach
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
