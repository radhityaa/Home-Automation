@extends('layouts.app')
@section('title', 'Remote Lampu')

@section('content')
    <div class="py-3">
        <button class="btn btn-success" onclick="create()"><i class="fa-solid fa-plus me-2"></i>Tambah</button>
    </div>
    <div class="card">
        <div class="card-body p-2 pt-3">
            <div class="mb-4">
                <h5 class="card-title">{{ $device->name }}</h5>
                <p class="card-text">{{ $device->description }}</p>
            </div>

            @foreach ($device->publishers as $item)
                <div class="mb-3">
                    <label class="switch switch-lg switch-success">
                        <input type="checkbox" class="switch-input" data-id="{{ $item->id }}"
                            data-topic="{{ $item->topic }}" />
                        <span class="switch-toggle-slider">
                            <span class="switch-on">
                                <i class="ti ti-check"></i>
                            </span>
                            <span class="switch-off">
                                <i class="ti ti-x"></i>
                            </span>
                        </span>
                        <span class="switch-label">{{ $item->name }}</span>
                    </label>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Modal -->
    <div>
        <div class="modal fade" id="modal-add" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modal-add-title">Tambah Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form method="POST" id="form-create">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label">Nama</label>
                                    <input type="text" id="name" name="name" class="form-control"
                                        placeholder="Ex. Lampu Depan" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="topic" class="form-label">Topic</label>
                                    <input type="text" id="topic" name="topic" class="form-control"
                                        placeholder="Ex. home/light1" required />
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="on_message" class="form-label">Pesan Saat Nyala</label>
                                    <input type="text" id="on_message" name="on_message" class="form-control"
                                        placeholder="Ex. Lampu Depan Nyala" required />
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="off_message" class="form-label">Pesan Saat Mati</label>
                                    <input type="text" id="off_message" name="off_message" class="form-control"
                                        placeholder="Ex. Lampu Depan Mati" required />
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

        function create() {
            $('#modal-add-title ').html('Tambah Baru')
            $('#modal-add').modal('show')
            url = "{{ route('publisher.store') }}"
            method = 'POST'
        }

        $(document).ready(function() {
            $('.switch-input').each(function() {
                var id = $(this).data('id');
                var url = "{{ route('get.state', ':id') }}"
                url = url.replace(':id', id);
                var switchInput = $(this);

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

                $.get(url, function(res) {
                    $.unblockUI();
                    var checked = res.state === 'ON' ? 'checked' : '';
                    switchInput.prop('checked', checked);
                })
            });

            $('.switch-input').change(function() {
                var id = $(this).data('id');
                var topic = $(this).data('topic');
                var message = $(this).is(':checked') ? 'ON' : 'OFF';

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
                    url: '/control-relay',
                    method: 'POST',
                    data: {
                        topic: topic,
                        message: message,
                        publisher_id: id
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        notyf.success(data.message);
                        $.unblockUI();
                    },
                    error: function(xhr, status, error) {
                        notyf.error(data.error);
                        $.unblockUI();
                    },
                    complete: function() {
                        $.unblockUI();
                    }
                });
            });

            $('#modal-add').on('hidden.bs.modal', function() {
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
                formData += '&device_id={{ $device->id }}';

                $.ajax({
                    url: url,
                    method: method,
                    data: formData,
                    success: function(res) {
                        notyf.success(res.message)
                        $('#modal-add').modal('hide')
                        $.unblockUI();

                        setTimeout(function() {
                            location.reload();
                        }, 1500);
                    },
                    error: function(res) {
                        notyf.error(res.responseJSON.message)
                        $('#modal-add').modal('hide')
                        $.unblockUI();
                    },
                    complete: function() {
                        $('#modal-add').modal('hide')
                        $.unblockUI();
                    }
                })
            })
        });
    </script>
@endpush
