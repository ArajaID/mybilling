@extends('adminlte::page')

@section('title', 'List Pelanggan')

@section('content_header')
    <h1>List Pelanggan</h1>
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
                                <th>No HP</th>
                                <th>Blok</th>
                                <th>Area</th>
                                <th>ODC dan ODP</th>
                                <th>Tanggal Aktivasi</th>
                                <th>Lama Berlangganan</th>
                                <th>User PPPoE</th>
                                <th>Password PPPoE</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($pelanggan as $data)
                                @php
                                    if ($data->area == 'perumahan') {
                                        $area = \Str::title($data->area);
                                    } else {
                                        $area = \Str::title(str_replace('_', ' ', $data->area));
                                    }

                                    $lamaBerlangganan = \Carbon\Carbon::parse($data->tanggal_aktivasi)->diffForHumans(
                                        null,
                                        true,
                                    );

                                @endphp


                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $data->kode_pelanggan }}</td>
                                    <td>{{ $data->nama_pelanggan }}</td>
                                    <td>{{ $data->no_telepon }}</td>
                                    <td>{{ \Str::upper($data->blok) }}</td>
                                    <td>{{ $area }}</td>
                                    <td>{{ $data->odpData->odc->odc_induk . '/' . $data->odpData->odc->odc . '/' . $data->odpData->odp }}
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($data->tanggal_aktivasi)->isoFormat('D MMM Y') }}</td>
                                    <td>{{ $lamaBerlangganan }}</td>

                                    <td>{{ $data->user_pppoe }}</td>
                                    <td>{{ $data->password_pppoe }}</td>
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
        </div>
    </div>

@stop
