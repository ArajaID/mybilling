@extends('adminlte::page')

@section('title', 'Laporan Pelanggan')

@section('content_header')
    <h1>Laporan Pelanggan</h1>
@stop

@section('content')

    <form action="" method="get">
        <div class="row">
            <div class="col-md-3">
                <x-adminlte-select2 name="is_active" label="Filter Aktif" data-placeholder="Pilih laporan...">
                    <option />
                    <option value="aktif">Aktif</option>
                    <option value="tidak-aktif">Tidak Aktif</option>
                </x-adminlte-select2>
            </div>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Cari</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">

                        <a href="" target="_blank" class="btn btn-info btn-sm"><i class="fas fa-download"></i>
                            PDF</a>

                    </h3>
                </div>
                <div class="p-0 card-body table-responsive" style="height: auto;">
                    <table class="table table-head-fixed text-nowrap">
                        <thead>
                            <tr>
                                <th style="width: 10px">No</th>
                                <th>Tanggal</th>
                                <th>Sumber</th>
                                <th>Kategori</th>
                                <th>Deskripsi</th>
                                <th>Debit</th>
                                <th>Kredit</th>
                                <th>Saldo</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
