@extends('layouts.app')
@section('title', 'Remote Control')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
    <div class="card-body">
        <div class="card-header mb-4">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <h3 class="card-title">Remote Control</h3>
                </div>
                <div class="col-md-6 text-end">
                    <button class="btn btn-sm btn-success" onclick="create()"><i class="fa-solid fa-plus me-2"></i>
                        Remote</button>
                </div>
            </div>
        </div>
        <div class="row mb-4">
            @foreach ($devices as $item)
                <div class="col-md-4 g-3">
                    <div class="card h-100 text-underline">
                        <div class="card-header d-flex justify-content-between">
                            <div class="card-title mb-0">
                                <div>
                                    <a href="{{ route('remote-control.view', $item->slug) }}">
                                        <span class="mb-0 fs-5">{{ $item->name }}</span>
                                    </a>
                                </div>
                                <div>
                                    <small class="text-muted">{{ $item->description }}</small>
                                </div>
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="MonthlyCampaign" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="ti ti-dots-vertical ti-sm text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="MonthlyCampaign">
                                    <button class="dropdown-item" onclick="edit('{{ $item->slug }}')">Ubah</button>
                                    <button class="dropdown-item" onclick="destroy('{{ $item->slug }}')">Hapus</button>
                                </div>
                            </div>
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

    <!-- Modal -->
    <div>
        <div class="modal fade" id="modal-device" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-device-title">Remote Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="form-create">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Masukan Nama Remote" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="description" class="form-label">Keterangan</label>
                                    <input type="text" id="description" name="description" class="form-control"
                                        placeholder="Keterangan Remote" required />
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-label-secondary" data-bs-dismiss="modal">
                            Tutup
                        </button>
                        <button type="submit" class="btn btn-primary btn-save">Simpan</button>
                    </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>

    <script>
        var notyf = new Notyf({
            position: {
                x: 'center',
                y: 'top'
            }
        })

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        let url = ''
        let method = ''

        function create() {
            $('#modal-device-title ').html('Tambah Remote Baru')
            $('#modal-device').modal('show')
            url = "{{ route('remote-control.store') }}"
            method = 'POST'
        }

        function edit(slug) {
            $('#modal-device-title').html('Ubah Remote')
            $('#modal-device').modal('show')

            let urlEdit = "{{ route('remote-control.edit', ':slug') }}"
            urlEdit = urlEdit.replace(':slug', slug)

            url = "{{ route('remote-control.update', ':slug') }}"
            url = url.replace(':slug', slug)
            method = 'PUT'

            $.ajax({
                url: urlEdit,
                method: 'GET',
                success: function(res) {
                    $('#name').val(res.name)
                    $('#description').val(res.description)
                }
            })
        }

        function destroy(slug) {
            url = "{{ route('remote-control.destroy', ':slug') }}"
            url = url.replace(':slug', slug)
            method = 'DELETE'

            Swal.fire({
                title: 'Apakah Yakin?',
                text: "Data yang sudah dihapus tidak dapat dikembalikan!",
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, Yakin!',
                cancelButtonText: 'Batal',
                customClass: {
                    confirmButton: 'btn btn-primary me-3 waves-effect waves-light',
                    cancelButton: 'btn btn-label-secondary waves-effect waves-light'
                },
                buttonsStyling: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    $.blockUI({
                        message: '<div class="spinner-border text-white" role="status"></div>',
                        css: {
                            backgroundColor: 'transparent',
                            border: '0'
                        },
                        overlayCSS: {
                            opacity: 0.5
                        }
                    });

                    $.ajax({
                        url: url,
                        method: method,
                        success: function(res) {
                            notyf.success(res.message)
                            setTimeout(function() {
                                window.location.reload();
                            }, 1500);
                            $.unblockUI()
                        },
                        error: function(res) {
                            notyf.error(res.responseJSON.message)
                            $.unblockUI()
                        },
                        complete: function() {
                            $.unblockUI()
                        }
                    })
                }
            });
        }

        $(document).ready(function() {
            $('#modal-device').on('hidden.bs.modal', function() {
                $('.modal-body form')[0].reset();
                $('input[name="_method"]').remove();
            })

            $('#form-create').on('submit', function(e) {
                e.preventDefault()

                $.blockUI({
                    message: '<div class="spinner-border text-white" role="status"></div>',
                    css: {
                        backgroundColor: 'transparent',
                        border: '0'
                    },
                    overlayCSS: {
                        opacity: 0.5
                    }
                });

                var formData = $('#form-create').serialize();

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(res) {
                        notyf.success(res.message)
                        $('#modal-device').modal('hide')
                        $.unblockUI();

                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(res) {
                        notyf.error(res.responseJSON.message)
                        $('#modal-device').modal('hide')
                        $.unblockUI();
                    },
                    complete: function() {
                        $('#modal-device').modal('hide')
                        $.unblockUI();
                    }
                })
            })
        })
    </script>
@endpush
