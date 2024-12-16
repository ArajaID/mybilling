@extends('adminlte::page')

@section('title', 'Tutup Buku')

@section('content_header')
    <h1>Tutup Buku</h1>
@stop

@section('content')
    <form action="" method="get">
        <div class="row">
            <x-adminlte-select name="bulan" label="Bulan" fgroup-class="col-md-2">
                <option value="0">Pilih Bulan</option>
                <option value="1" {{ request('bulan') == '1' ? 'selected' : '' }}>Januari</option>
                <option value="2" {{ request('bulan') == '2' ? 'selected' : '' }}>Februari</option>
                <option value="3" {{ request('bulan') == '3' ? 'selected' : '' }}>Maret</option>
                <option value="4" {{ request('bulan') == '4' ? 'selected' : '' }}>April</option>
                <option value="5" {{ request('bulan') == '5' ? 'selected' : '' }}>Mei</option>
                <option value="6" {{ request('bulan') == '6' ? 'selected' : '' }}>Juni</option>
                <option value="7" {{ request('bulan') == '7' ? 'selected' : '' }}>Juli</option>
                <option value="8" {{ request('bulan') == '8' ? 'selected' : '' }}>Agustus</option>
                <option value="9" {{ request('bulan') == '9' ? 'selected' : '' }}>September</option>
                <option value="10" {{ request('bulan') == '10' ? 'selected' : '' }}>Oktober</option>
                <option value="11" {{ request('bulan') == '11' ? 'selected' : '' }}>Nopember</option>
                <option value="12" {{ request('bulan') == '12' ? 'selected' : '' }}>Desember</option>
            </x-adminlte-select>

            <x-adminlte-select name="tahun" label="Tahun" fgroup-class="col-md-2">
                <option value="0">Pilih Tahun</option>
                <option value="2024" {{ request('tahun') == '2024' ? 'selected' : '' }}>2024</option>
                <option value="2025" {{ request('tahun') == '2025' ? 'selected' : '' }}>2025</option>
                <option value="2026" {{ request('tahun') == '2026' ? 'selected' : '' }}>2026</option>
                <option value="2027" {{ request('tahun') == '2027' ? 'selected' : '' }}>2027</option>
                <option value="2028" {{ request('tahun') == '2028' ? 'selected' : '' }}>2028</option>
                <option value="2029" {{ request('tahun') == '2029' ? 'selected' : '' }}>2029</option>
                <option value="2030" {{ request('tahun') == '2030' ? 'selected' : '' }}>2030</option>
            </x-adminlte-select>
        </div>

        <div class="mb-3 row">
            <div class="col-md-2">
                <button type="submit" class="btn btn-primary">Filter</button>
            </div>
        </div>
    </form>

    <div class="text-center ">
        <div class="p-2 card">
            <h2 class="font-italic">Sebelum tutup buku diklik, harap cek data terlebih dahulu !</h2>
        </div>
    </div>

    <div class="row">
        <div class="col-md-12">
            @if (request('bulan') && request('tahun'))
                @if ($dataTransaksi->isEmpty())
                    <x-adminlte-alert class="text-center bg-warning" icon="fas fa-lg fa-exclamation-triangle" title="Oops"
                        dismissable>
                        Tidak ada transaksi yang ditemukan dalam bulan dan tahun yang dipilih.
                    </x-adminlte-alert>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">

                                <a href=""
                                    onclick="this.href='/tutup-buku/' + document.getElementById('bulan').value + '/' + document.getElementById('tahun').value"
                                    class="btn btn-success btn-sm"><i class="fas fa-check"></i>
                                    Tutup Buku</a>

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
