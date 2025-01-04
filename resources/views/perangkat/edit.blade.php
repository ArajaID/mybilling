@extends('adminlte::page')

@section('title', 'Edit Perangkat')

@section('content_header')
    <h1>Edit Perangkat</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card card-danger">
                <form action="{{ route('perangkat.update', $perangkat->id) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="card-body">

                        <div class="row">
                            <x-adminlte-input name="nama_perangkat" label="Nama Perangkat" fgroup-class="col-md-4"
                                value="{{ old('nama_perangkat', $perangkat->nama_perangkat) }}" />
                            <x-adminlte-input name="merek" label="Merek" fgroup-class="col-md-2"
                                value="{{ old('merek', $perangkat->merek) }}" />
                            <x-adminlte-input name="tipe" label="Tipe" fgroup-class="col-md-2"
                                value="{{ old('tipe', $perangkat->tipe) }}" />
                            <x-adminlte-input name="sn" label="Serial Number" fgroup-class="col-md-4"
                                value="{{ old('sn', $perangkat->sn) }}" />


                        </div>

                        <div class="row">
                            <x-adminlte-select name="is_active" label="Status Aktif" fgroup-class="col-md-2">
                                <option value="1" {{ old('is_active', $perangkat->is_active) == 1 ? 'selected' : '' }}>
                                    Aktif</option>
                                <option value="0" {{ old('is_active', $perangkat->is_active) == 0 ? 'selected' : '' }}>
                                    Tidak Aktif</option>
                            </x-adminlte-select>

                            <x-adminlte-textarea name="catatan" label="Catatan" fgroup-class="col-md-10"
                                value="{{ old('catatan', $perangkat->catatan) }}">
                                {{ $perangkat->catatan }}
                            </x-adminlte-textarea>
                        </div>
                    </div>
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Simpan</button>
                        <a href="{{ route('perangkat.index') }}" class="btn btn-warning">Batal</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@stop
