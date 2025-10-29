@extends('layouts.admin.app')

@section('title', translate('activity detail'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/activitie.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('activity_detail') }}
            </h2>
            {{-- <span class="badge badge-soft-dark rounded-50 fs-14">{{$activities->total()}}</span> --}}
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">

                    <div class="p-3">
                        <div>
                            <span class="text-fs" style="font-weight: bold">
                                {{ \App\CentralLogics\translate('title') }}
                            </span>
                            <p>{{ $activity->description }}</p>
                        </div>

                        <div>
                            <span class="text-fs" style="font-weight: bold">
                                {{ \App\CentralLogics\translate('date_and_time') }}
                            </span>
                            <p>
                                {{ \Carbon\Carbon::parse($activity->created_at)->format('M j, Y g:i A') }}
                            </p>
                        </div>

                        <div>
                            <span class="text-fs" style="font-weight: bold">
                                {{ \App\CentralLogics\translate('User') }}
                            </span>
                            <p>{{ $activity->causer->f_name }} {{ $activity->causer->l_name }}</p>
                        </div>
                    </div>


                </div>
                @if (isset($activity->properties['attributes']) &&
                        is_array($activity->properties['attributes']) &&
                        count($activity->properties['attributes']) > 0)
                    @php
                        $activityProperties = $activity->properties['attributes'];
                        $oldValues = isset($activity->properties['old']) ? $activity->properties['old'] : [];
                        $text = $oldValues ? ' New Value ' : ' Value ';
                    @endphp

                    <div class="card p-3 mt-3">
                        <h1>Detail of Order</h1>

                        <table>
                            <tr>
                                <th style="font-weight: bold">Attribute</th>
                                <th style="font-weight: bold">{{ $text }}</th>
                                @if (!empty($oldValues))
                                    <th style="font-weight: bold">Old Value</th>
                                @endif
                            </tr>
                            @foreach ($activityProperties as $key => $newValue)
                                <tr>
                                    <td>{{ $key }}</td>
                                    <td>{{ $newValue }}</td>
                                    @if (!empty($oldValues))
                                        <td>{{ isset($oldValues[$key]) ? $oldValues[$key] : 'N/A' }}</td>
                                    @endif
                                </tr>
                            @endforeach
                        </table>
                    </div>
                @endif


                {{-- <table>
        <tr>
            <th>Attribute</th>
            <th>Value</th>
        </tr>
        @foreach ($orderData['detail'] as $key => $value)
            <tr>
                <td>{{ $key }}</td>
                <td>{{ $value }}</td>
            </tr>
        @endforeach
    </table> --}}

                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', d() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.business-settings.search') }}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $('#loading').show();
                },
                success: function(data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function() {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
