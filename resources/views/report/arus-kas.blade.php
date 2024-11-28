@extends('adminlte::page')

@section('title', 'Laporan Keuangan')

@section('content_header')
    <h1>Laporan Keuangan</h1>
@stop

@section('content')
    <form action="" method="get">
        <div class="row">
            <div class="col-md-3">
                <x-adminlte-select2 name="bulan" label="Bulan" data-placeholder="Pilih bulan...">
                    <option />
                    <option value="1" {{ $bulan == '1' ? 'selected' : '' }}>Januari</option>
                    <option value="2" {{ $bulan == '2' ? 'selected' : '' }}>Februari</option>
                    <option value="3" {{ $bulan == '3' ? 'selected' : '' }}>Maret</option>
                    <option value="4" {{ $bulan == '4' ? 'selected' : '' }}>April</option>
                    <option value="5" {{ $bulan == '5' ? 'selected' : '' }}>Mei</option>
                    <option value="6" {{ $bulan == '6' ? 'selected' : '' }}>Juni</option>
                    <option value="7" {{ $bulan == '7' ? 'selected' : '' }}>Juli</option>
                    <option value="8" {{ $bulan == '8' ? 'selected' : '' }}>Agustus</option>
                    <option value="9" {{ $bulan == '9' ? 'selected' : '' }}>September</option>
                    <option value="10" {{ $bulan == '10' ? 'selected' : '' }}>Oktober</option>
                    <option value="11" {{ $bulan == '11' ? 'selected' : '' }}>Nopember</option>
                    <option value="12" {{ $bulan == '12' ? 'selected' : '' }}>Desember</option>
                </x-adminlte-select2>
            </div>

            <div class="col-md-3">
                <x-adminlte-select2 name="tahun" label="Tahun" data-placeholder="Pilih tahun...">
                    <option />
                    <option value="2023" {{ $tahun == '2023' ? 'selected' : '' }}>2023</option>
                    <option value="2024" {{ $tahun == '2024' ? 'selected' : '' }}>2024</option>
                    <option value="2025" {{ $tahun == '2025' ? 'selected' : '' }}>2025</option>
                    <option value="2026" {{ $tahun == '2026' ? 'selected' : '' }}>2026</option>
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

                            @php
                                $no = 1;
                                $saldo = 0;
                            @endphp

                            @foreach ($dataTransaksi as $data)
                                <tr>
                                    <td style="text-align: center">{{ $no++ }}</td>
                                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('d-m-Y') }}</td>
                                    <td>{{ $data->sumber }}</td>
                                    <td>{{ $data->kategori }}</td>
                                    <td>{{ $data->deskripsi }}</td>
                                    <td>
                                        @if ($data->debit)
                                            @currency($data->debit)
                                        @endif
                                    </td>
                                    <td>
                                        @if ($data->kredit)
                                            @currency($data->kredit)
                                        @endif
                                    </td>
                                    @php
                                        $saldo += $data->debit - $data->kredit;
                                    @endphp
                                    <td>@currency($saldo)</td>
                                </tr>
                            @endforeach

                            <tr>
                                <td colspan="5" style="text-align: center">Total</td>
                                <td>@currency($totalDebit)</td>
                                <td>@currency($totalKredit)</td>
                                <td>@currency($totalDebit - $totalKredit)</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@stop
