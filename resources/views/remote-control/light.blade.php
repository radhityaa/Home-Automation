@extends('layouts.app')
@section('title', 'Remote Lampu')

@section('content')
    <div class="card">
        <div class="card-header">
            <div class="d-flex justify-content-between align-items-center">
                <span class="badge bg-label-success">Connected</span>
                <span class="text-muted">{{ date_format(now(), 'd M Y H:i:s') }}</span>
            </div>
        </div>
        <div class="card-body">
            <div class="mb-4">
                <h5 class="card-title">Kontrol Lampu</h5>
                <p class="card-text">Remote kontrol untuk on off lampu</p>
            </div>

            <div class="col-md-6">
                <div class="demo-inline-spacing" id="relay-controls">

                </div>
            </div>
        </div>
    </div>
@endsection

@push('script')
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        })

        $(document).ready(function() {
            loadRelayStatus();

            function loadRelayStatus() {
                $.ajax({
                    url: '/relay-status',
                    method: 'GET',
                    success: function(data) {
                        var relayControls = $('#relay-controls');
                        relayControls.empty();
                        data.forEach(function(relay) {
                            var checked = relay.state === 'ON' ? 'checked' : '';
                            relayControls.append(`
                            <label class="switch switch-success">
                                <input type="checkbox" class="switch-input" data-relay="${relay.relay_number}" ${checked} />
                                <span class="switch-toggle-slider">
                                    <span class="switch-on">
                                        <i class="ti ti-check"></i>
                                    </span>
                                    <span class="switch-off">
                                        <i class="ti ti-x"></i>
                                    </span>
                                </span>
                                <span class="switch-label">Lampu ${relay.relay_number}</span>
                            </label>
                        `);
                        });

                        $('.switch-input').change(function() {
                            var relay = $(this).data('relay');
                            var state = $(this).is(':checked') ? 'on' : 'off';
                            controlRelay(relay, state);
                        });
                    }
                });
            }

            function controlRelay(relay, state) {
                $.ajax({
                    url: '/control-relay',
                    method: 'POST',
                    data: {
                        relay: relay,
                        state: state
                    },
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    success: function(data) {
                        alert(data.message);
                    }
                });
            }
        });
    </script>
@endpush
