@extends('adminlte::page')

@section('title', 'Daftar Perangkat')

@section('content_header')
    <h1>Daftar Perangkat</h1>
@stop

@section('content')
    <div class="mb-2">
        <a href={{ route('perangkat.create') }} class="btn btn-primary ">Tambah Perangkat</a>
    </div>

    <div class="card">
        <div class="p-0 card-body">
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Perangkat</th>
                        <th>Merek</th>
                        <th>Tipe</th>
                        <th>SN</th>
                        <th>Pelanggan</th>
                        <th>Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($perangkats as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data->nama_perangkat }}</td>
                            <td>{{ $data->merek }}</td>
                            <td>{{ $data->tipe }}</td>
                            <td>{{ $data->sn }}</td>
                            <td>{{ $data->pelanggan ? '(' . $data->pelanggan->kode_pelanggan . ') ' . $data->pelanggan->nama_pelanggan : 'Belum ada Pelanggan' }}
                            </td>
                            <td>{!! $data->is_active
                                ? "<span class='badge badge-success'>Aktif</span>"
                                : "<span class='badge badge-danger'>Tidak Aktif</span>" !!}
                            </td>
                            <td>
                                <a href="{{ route('perangkat.edit', $data->id) }}" class="btn btn-primary btn-sm">Edit</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            <div class="clearfix card-footer">
                <ul class="float-right m-0 pagination pagination-sm">
                    {{ $perangkats->links() }}
                </ul>
            </div>
        </div>
    </div>
@stop

@section('css')

@stop

@section('js')

@stop
