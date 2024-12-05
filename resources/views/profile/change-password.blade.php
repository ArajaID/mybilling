@extends('adminlte::page')

@section('title', 'Profil')

@section('content_header')
    <h1>Profil</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-3">
            <div class="card card-primary card-outline">
                <div class="card-body box-profile">
                    <form action="{{ route('update-password') }}" method="POST">
                        @csrf

                        <x-adminlte-input type="password" name="old_password" label="Kata Sandi Lama" fgroup-class="col-md-12"
                            required />

                        <x-adminlte-input type="password" name="new_password" label="Kata Sandi Baru"
                            fgroup-class="col-md-12" required />

                        <x-adminlte-input type="password" name="new_password_confirmation" label="Konfirmasi Kata Sandi"
                            fgroup-class="col-md-12" required />

                        <button type="submit" class="btn btn-primary btn-block"><b>Update Kata Sandi</b></button>
                    </form>

                </div>
            </div>
        </div>

        <div class="col-md-9">
            <div class="card card-primary card-outline">

                <div class="card-body">
                    <div class="tab-content">

                        <div class="active tab-pane" id="settings">
                            <div class="form-row">
                                <x-adminlte-input name="name" label="Nama" fgroup-class="col-md-6"
                                    value="{{ auth()->user()->name }}" disabled />
                                <x-adminlte-input type="email" name="email" label="Email" fgroup-class="col-md-6"
                                    value="{{ auth()->user()->email }}" disabled />
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
@stop
