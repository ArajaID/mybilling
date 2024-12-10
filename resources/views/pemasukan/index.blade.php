@extends('adminlte::page')

@section('title', 'Pemasukan')

@section('content_header')
    <h1>Pemasukan</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('pemasukan.create') }} class="btn btn-primary ">Tambah Pemasukan</a>
    </div>

    <div class="card">
        <div class="p-0 card-body table-responsive" style="height: auto;">
            <table class="table table-head-fixed text-nowrap">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Tanggal</th>
                        <th>Sumber</th>
                        <th>Kategori</th>
                        <th>Deskripsi</th>
                        <th>Metode Pembayaran</th>
                        <th>Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pemasukan as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ \Carbon\Carbon::parse($data->tanggal)->format('d-m-Y') }}</td>
                            <td>{{ $data->sumber }}</td>
                            <td>{{ $data->kategori }}</td>
                            <td>{{ $data->deskripsi }}</td>
                            <td>{{ $data->metode_pembayaran }}</td>
                            <td>@currency($data->debit)</td>
                            <td>
                                <a href="{{ route('pemasukan.edit', $data->id) }}" class="btn btn-sm btn-primary"><i
                                        class="fas fa-edit"></i></a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="clearfix card-footer">
                <ul class="float-right m-0 pagination pagination-sm">
                    {{ $pemasukan->links() }}
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
