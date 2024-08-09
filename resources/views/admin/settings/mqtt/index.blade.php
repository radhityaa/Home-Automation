@extends('layouts.app')
@section('title', 'MQTT Setting')

@push('css')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
@endpush

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>MQTT Setting</h4>
        </div>
        <div class="card-body">
            <form action="" method="POST" id="form-update">

                <div class="row mb-4">
                    <div class="form-group col-md-4">
                        <label for="host">Host</label>
                        <input type="text" class="form-control" id="host" name="host"
                            placeholder="ex: 123.456.78.15" value="{{ old('host', $data->host) }}" required autofocus>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="port">Port</label>
                        <input type="number" class="form-control" id="port" name="port" placeholder="1883"
                            value="{{ old('port', $data->port) }}" required>
                    </div>

                    <div class="form-group col-md-4">
                        <label for="client_id">Client ID</label>
                        <input type="text" class="form-control" id="client_id" name="client_id"
                            placeholder="mqtt-laravel" value="{{ old('client_id', $data->client_id) }}" required>
                    </div>
                </div>

                <div class="row mb-4">
                    <div class="form-group col-md-6">
                        <label for="username">Username</label>
                        <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                            value="{{ old('username', $data->username) }}">
                    </div>

                    <div class="form-group col-md-6">
                        <label for="password">Password</label>
                        <input type="text" class="form-control" id="password" name="password" placeholder="Password"
                            value="{{ old('password', $data->password) }}">
                    </div>
                </div>

                <button type="submit" class="btn btn-primary btn-save">Ubah</button>
                <x-button-loading />
                <button type="button" class="btn btn-warning btn-check-connection">Cek Koneksi</button>
            </form>
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
        });

        $(document).ready(function() {
            $('#form-update').on('submit', function(e) {
                e.preventDefault()
                $('.card-body form').append('<input type="hidden" name="_method" value="PUT">');

                $('.btn-save').addClass('d-none')
                $('.btn-loading').removeClass('d-none')

                let url = "{{ route('admin.settings.mqtt.update', ':id') }}"
                url = url.replace(':id', '{{ $data->id }}')

                $.ajax({
                    url: url,
                    method: 'POST',
                    data: $(this).serialize(),
                    success: function(res) {
                        notyf.success(res.message)
                        $('.btn-save').removeClass('d-none')
                        $('.btn-loading').addClass('d-none')
                    },
                    error: function(res) {
                        notyf.error(res.responseJSON.message);
                        $('.btn-save').removeClass('d-none')
                        $('.btn-loading').addClass('d-none')
                    },
                    complete: function() {
                        $('.btn-save').removeClass('d-none')
                        $('.btn-loading').addClass('d-none')
                    }
                })
            })

            $('.btn-check-connection').on('click', function() {
                let url = "{{ route('admin.settings.mqtt.check') }}"
                $('input[name="_method"]').remove();

                $('.btn-check-connection').addClass('d-none')
                $('.btn-loading').removeClass('d-none')

                $.ajax({
                    url: url,
                    method: "POST",
                    data: $('#form-update').serialize(),
                    success: function(res) {
                        $('.btn-check-connection').removeClass('d-none')
                        $('.btn-loading').addClass('d-none')
                        notyf.success(res.message)
                    },
                    error: function(res) {
                        $('.btn-check-connection').removeClass('d-none')
                        $('.btn-loading').addClass('d-none')

                        Swal.fire({
                            title: 'Error',
                            text: res.responseJSON.message,
                            icon: 'error',
                            customClass: {
                                confirmButton: 'btn btn-primary waves-effect waves-light'
                            },
                            buttonsStyling: false
                        })
                    },
                    complete: function() {
                        $('.btn-check-connection').removeClass('d-none')
                        $('.btn-loading').addClass('d-none')
                    }
                })
            })
        })
    </script>
@endpush
