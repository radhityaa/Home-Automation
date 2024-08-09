@extends('layouts.app')
@section('title', 'Remote Lampu')

@section('content')
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
@endsection

@push('script')
    <script src="{{ asset('assets/vendor/libs/block-ui/block-ui.js') }}"></script>

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
                        notyf.success(data.message === 'ON' ?
                            'Lampu Berhasil Di Nyalakan' : 'Lampu Berhasil Di Matikan');
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
        });
    </script>
@endpush
