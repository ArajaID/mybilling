@extends('adminlte::page')

@section('title', 'Laporan Keuangan')

@section('content_header')
    <h1>Laporan Keuangan</h1>
@stop

@section('content')
    <form action="" method="get">
        <div class="row">
            <x-adminlte-input type="date" name="start_date" id="start_date" label="Tanggal Mulai" fgroup-class="col-md-3"
                value="{{ request('start_date') }}" required />

            <x-adminlte-input type="date" name="end_date" id="end_date" label="Tanggal Akhir" fgroup-class="col-md-3"
                value="{{ request('end_date') }}" required />
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="row">
        <div class="col-md-12">
            @if (request('start_date') && request('end_date'))
                @if ($dataTransaksi->isEmpty())
                    <x-adminlte-alert class="text-center bg-warning" icon="fas fa-lg fa-exclamation-triangle" title="Oops"
                        dismissable>
                        Tidak ada transaksi yang ditemukan dalam rentang tanggal yang dipilih.
                    </x-adminlte-alert>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">

                                <a href="" target="_blank"
                                    onclick="this.href='/report/arus-kas-pdf/' + document.getElementById('start_date').value + '/' + document.getElementById('end_date').value"
                                    class="btn btn-info btn-sm"><i class="fas fa-download"></i>
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

                                    @php
                                        $no = 1;
                                        $saldo = $saldoBulanSebelumnya;
                                    @endphp

                                    <!-- Baris saldo bulan sebelumnya -->
                                    <tr>
                                        <td colspan="7" style="text-align: center"><strong>Saldo Bulan
                                                Sebelumnya</strong></td>
                                        <td style="text-align: right"><strong>@currency($saldoBulanSebelumnya)</strong></td>
                                    </tr>

                                    @foreach ($dataTransaksi as $data)
                                        <tr>
                                            <td style="text-align: center">{{ $no++ }}</td>
                                            <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                                            <td>{{ $data->sumber }}</td>
                                            <td>{{ $data->kategori }}</td>
                                            <td>{{ $data->deskripsi }}</td>
                                            <td style="text-align: right">
                                                @if ($data->debit)
                                                    @currency($data->debit)
                                                @endif
                                            </td>
                                            <td style="text-align: right">
                                                @if ($data->kredit)
                                                    @currency($data->kredit)
                                                @endif
                                            </td>
                                            @php
                                                $saldo += $data->debit - $data->kredit;
                                            @endphp
                                            <td style="text-align: right">@currency($saldo)</td>
                                        </tr>
                                    @endforeach

                                    <tr style="text-align: right">
                                        <td colspan="5" style="text-align: center">Total</td>
                                        <td>@currency($totalDebit)</td>
                                        <td>@currency($totalKredit)</td>
                                        <td>@currency($totalDebit - $totalKredit)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>

                    </div>
                @endif
            @endif
        </div>
    </div>
@stop
