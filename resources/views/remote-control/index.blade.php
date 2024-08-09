@extends('layouts.app')
@section('title', 'Remote Control')

@section('content')
    <div class="card-body">
        <div class="card-header mb-4">
            <div class="row">
                <div class="col-md-6">
                    <h3 class="card-title">Remote Control</h3>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-success"><i class="fa-solid fa-plus me-2"></i> Perangkat</button>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            @foreach ($devices as $item)
                <div class="col-md-4 g-3">
                    <div class="card p-2 h-100 shadow-lg">
                        <div class="card-body p-3 pt-2">
                            <div class="d-flex justify-content-between align-items-center mb-3">
                            </div>
                            <a href="{{ route($item->route, $item->id) }}" class="h5">{{ $item->name }}</a>
                            <p class="mt-2">{{ $item->description }}</p>
                            <a class="app-academy-md-50 btn btn-label-primary d-flex align-items-center"
                                href="{{ route($item->route, $item->id) }}">
                                <span class="me-2">Remote</span><i class="ti ti-chevron-right scaleX-n1-rtl ti-sm"></i>
                            </a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        {{-- <nav aria-label="Page navigation" class="d-flex align-items-center justify-content-center">
            <ul class="pagination">
                <li class="page-item prev">
                    <a class="page-link" href="javascript:void(0);"><i
                            class="ti ti-chevron-left ti-xs scaleX-n1-rtl"></i></a>
                </li>
                <li class="page-item active">
                    <a class="page-link" href="javascript:void(0);">1</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);">2</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);">3</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);">4</a>
                </li>
                <li class="page-item">
                    <a class="page-link" href="javascript:void(0);">5</a>
                </li>
                <li class="page-item next">
                    <a class="page-link" href="javascript:void(0);"><i
                            class="ti ti-chevron-right ti-xs scaleX-n1-rtl"></i></a>
                </li>
            </ul>
        </nav> --}}
    </div>
@endsection
