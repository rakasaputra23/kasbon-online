@extends('adminlte::page')

@section('title', 'Staff Dashboard')

@section('content_header')
    <h1>Dashboard Staff</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">{{ $stats['welcome_message'] }}</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>Informasi Profil</h5>
                            <table class="table table-borderless">
                                <tr>
                                    <td><strong>NIP:</strong></td>
                                    <td>{{ $stats['user_info']->nip }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Nama:</strong></td>
                                    <td>{{ $stats['user_info']->nama }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Posisi:</strong></td>
                                    <td>{{ $stats['user_info']->posisi }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Role:</strong></td>
                                    <td>
                                        <span class="badge badge-primary">
                                            {{ $stats['user_info']->userGroup->name ?? 'N/A' }}
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Email:</strong></td>
                                    <td>{{ $stats['user_info']->email }}</td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            <h5>Quick Actions</h5>
                            <div class="row">
                                <div class="col-12 mb-2">
                                    <a href="#" class="btn btn-success btn-block">
                                        <i class="fas fa-plus"></i> Ajukan Kasbon Baru
                                    </a>
                                </div>
                                <div class="col-12 mb-2">
                                    <a href="#" class="btn btn-info btn-block">
                                        <i class="fas fa-list"></i> Lihat Riwayat Kasbon
                                    </a>
                                </div>
                                <div class="col-12 mb-2">
                                    <a href="#" class="btn btn-warning btn-block">
                                        <i class="fas fa-user-edit"></i> Edit Profil
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@stop

@section('css')
    <style>
        .table-borderless td {
            border: none;
            padding: 0.3rem 0.75rem;
        }
        .btn-block {
            margin-bottom: 10px;
        }
    </style>
@stop

@section('js')
    <script>
        console.log('Staff Dashboard Loaded!');
    </script>
@stop