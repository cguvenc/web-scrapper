@extends('back.layouts.master')
@section('title', 'Anasayfa')
@section('content')
    <div class="d-flex flex-column flex-column-fluid">
        <div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6 col-12">
            <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
                <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
                    <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
                        <li class="breadcrumb-item text-muted">
                            <a href="{{ route('dashboard') }}" class="text-muted text-hover-primary">Anasayfa</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div id="kt_app_content" class="app-content flex-column-fluid">
            <div id="kt_app_content_container" class="app-container container-fluid">
                <div class="row">
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="px-2 py-6 fs-3 text-primary">Mağazalar</h2>
                            </div>
                            <div class="card-body">
                                <h2 class="px-2">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 mt-md-0 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="px-2 py-6 fs-3 text-primary">Ürünler</h2>
                            </div>
                            <div class="card-body">
                                <h2 class="px-2">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="px-2 py-6 fs-3 text-primary">Son Çekilen Ürünler</h2>
                            </div>
                            <div class="card-body">
                                <h2 class="px-2">0</h2>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-3 mt-md-0 mt-4">
                        <div class="card">
                            <div class="card-header">
                                <h2 class="px-2 py-6 fs-3 text-primary"> Aktif Mağazalar</h2>
                            </div>
                            <div class="card-body">
                                <h2 class="px-2">0</h2>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
