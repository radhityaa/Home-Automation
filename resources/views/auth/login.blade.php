@extends('layouts.auth')

@section('title')
    Login
@endsection

@section('content')
    <div class="card">
        <div class="card-body">
            <!-- Logo -->
            <div class="app-brand justify-content-center mb-4 mt-2">
                <span class="app-brand-logo demo">
                    <svg width="32" height="22" viewBox="0 0 32 22" fill="none" xmlns="http://www.w3.org/2000/svg">
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M0.00172773 0V6.85398C0.00172773 6.85398 -0.133178 9.01207 1.98092 10.8388L13.6912 21.9964L19.7809 21.9181L18.8042 9.88248L16.4951 7.17289L9.23799 0H0.00172773Z"
                            fill="#7367F0" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.69824 16.4364L12.5199 3.23696L16.5541 7.25596L7.69824 16.4364Z" fill="#161616" />
                        <path opacity="0.06" fill-rule="evenodd" clip-rule="evenodd"
                            d="M8.07751 15.9175L13.9419 4.63989L16.5849 7.28475L8.07751 15.9175Z" fill="#161616" />
                        <path fill-rule="evenodd" clip-rule="evenodd"
                            d="M7.77295 16.3566L23.6563 0H32V6.88383C32 6.88383 31.8262 9.17836 30.6591 10.4057L19.7824 22H13.6938L7.77295 16.3566Z"
                            fill="#7367F0" />
                    </svg>
                </span>
                <span class="app-brand-text demo text-body fw-bold ms-1">{{ env('APP_NAME') }}</span>
            </div>
            <!-- /Logo -->
            <h4 class="mb-1 pt-2">Selamat Datang! 👋</h4>
            <p class="mb-4">Silahkan Login Terlebih Dahulu</p>

            <form id="form-login" class="mb-3">
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control" id="username" name="username" placeholder="Username"
                        autofocus />
                </div>
                <div class="mb-3 form-password-toggle">
                    <div class="d-flex justify-content-between">
                        <label class="form-label" for="password">Password</label>
                    </div>
                    <div class="input-group input-group-merge">
                        <input type="password" id="password" class="form-control" name="password"
                            placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"
                            aria-describedby="password" />
                        <span class="input-group-text cursor-pointer"><i class="ti ti-eye-off"></i></span>
                    </div>
                </div>
                <div class="mb-3">
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="remember-me" />
                        <label class="form-check-label" for="remember-me"> Remember Me </label>
                    </div>
                </div>
                <div class="mb-3">
                    <button class="btn btn-primary d-grid w-100 btn-login" type="submit">Login</button>
                    <x-loading />
                </div>
            </form>
        </div>
    </div>
@endsection

@push('script')
    <script>
        let btnLogin = $('.btn-login');
        let btnLoading = $('.btn-loading');
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

        $(document).ready(function() {

            $('#form-login').on('submit', function(e) {
                e.preventDefault();
                btnLogin.addClass('d-none')
                btnLoading.removeClass('d-none')

                var formData = $(this).serialize();

                $.ajax({
                    url: "{{ route('auth.login') }}",
                    method: 'POST',
                    data: formData,
                    success: function(res) {
                        btnLogin.removeClass('d-none')
                        btnLoading.addClass('d-none')

                        notyf.success({
                            message: 'Login berhasil'
                        });

                        setInterval(function() {
                            window.location.href = "{{ route('dashboard') }}";
                        }, 2000);
                    },
                    error: function(res) {
                        btnLogin.removeClass('d-none');
                        btnLoading.addClass('d-none');

                        if (res.responseJSON && res.responseJSON.errors) {
                            // Jika res.responseJSON.errors adalah objek
                            Object.values(res.responseJSON.errors).forEach(messages => {
                                // messages di sini adalah array dari pesan error
                                messages.forEach(message => {
                                    notyf.error({
                                        message: message
                                    });
                                });
                            });
                        } else if (res.responseJSON && res.responseJSON.message) {
                            notyf.error({
                                message: res.responseJSON.message
                            });
                        } else {
                            notyf.error({
                                message: 'An unknown error occurred.'
                            });
                        }
                    }

                });
            })
        })
    </script>
@endpush
