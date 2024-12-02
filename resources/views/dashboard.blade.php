@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop

@section('content')
    <div class="main">

        <div class="row">

            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('pelanggan-online') }}">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>{{ $pppActive }} Online</h3>

                            <p>Pelanggan Online</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-stalker"></i>
                        </div>
                    </div>
                </a>
            </div>

            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('pelanggan.index') }}">
                    <div class="small-box bg-success">
                        <div class="inner">
                            <h3>{{ $totalPelanggan }}</h3>
                            <p>Total Pelanggan</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-person-add"></i>
                        </div>
                    </div>
                </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('tagihan.index') }}">
                    <div class="small-box bg-warning">
                        <div class="inner">
                            <h3>{{ $tagihanActive }} Tagihan</h3>

                            <p>Belum Lunas</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-card"></i>
                        </div>
                    </div>
                </a>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-6">
                <!-- small box -->
                <a href="{{ route('paket.index') }}">
                    <div class="small-box bg-danger">
                        <div class="inner">
                            <h3>{{ $paketActive }} Paket</h3>

                            <p>Internet Aktif</p>
                        </div>
                        <div class="icon">
                            <i class="ion ion-wifi"></i>
                        </div>
                    </div>
                </a>
            </div>
            <!-- ./col -->
        </div>

        {{-- <div class="row">
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Resource</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td style="width: 20%">CPU</td>
                                    <td colspan="2">Update software</td>
                                </tr>

                                <tr>
                                    <td>Memory</td>
                                    <td colspan="2">Update software</td>
                                </tr>

                                <tr>
                                    <td>HDD</td>
                                    <td colspan="2">Update software</td>
                                </tr>

                                <tr>
                                    <td>Health</td>
                                    <td>Update software</td>
                                    <td style="width: 40%">a</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">System Info</h3>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table class="table table-bordered">
                            <tbody>
                                <tr>
                                    <td style="width: 35%">Uptime</td>
                                    <td>Update software</td>
                                </tr>

                                <tr>
                                    <td>Board Name</td>
                                    <td>Update software</td>
                                </tr>

                                <tr>
                                    <td>Model</td>
                                    <td>Update software</td>
                                </tr>

                                <tr>
                                    <td>Router OS</td>
                                    <td>Update software</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div> --}}
    </div>
@stop

@section('css')
    <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
@stop

@section('js')
    <script>
        console.log("Hi, I'm using the Laravel-AdminLTE package!");
    </script>
@stop
