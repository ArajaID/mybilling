@extends('adminlte::page')

@section('title', 'Pelanggan Online')

@section('content_header')
    <h1>Pelanggan Online</h1>
@stop

@section('content')
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">{{ $totalOnline }} Pelanggan Online</h3>

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
                        <th>User PPPoE</th>
                        <th>Caller ID</th>
                        <th>IP Address</th>
                        <th>Uptime</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($pppActive as $data)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $data['name'] }}</td>
                            <td>{{ $data['caller-id'] }}</td>
                            <td>{{ $data['address'] }}</td>
                            <td>{{ $data['uptime'] }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="clearfix card-footer">
                <ul class="float-right m-0 pagination pagination-sm">

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
