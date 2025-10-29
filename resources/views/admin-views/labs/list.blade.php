@extends('layouts.admin.app')

@section('title', translate('Labratory list'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">

                {{ \App\CentralLogics\translate('Labratory list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $totalTestTypes }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by patient Name') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('patient.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.patient.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('add_new_patient') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Patient') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Test Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Category') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Unit') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Shade') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>
                            @php
                                $counter = $medicalHistories->firstItem(); // Initialize counter with the first item of pagination
                            @endphp
                            <tbody id="set-rows">
                                @foreach ($medicalHistories as $key => $history)
                                    @foreach ($history->testTypes as $index => $testType)
                                        <tr>
                                            <td>{{ $counter++ }}</td>
                                            <td>{{ $history->patient->full_name }}</td>
                                            <td>{{ $testType->test_name }}</td>
                                            <td>{{ $testType->test_category }}</td>
                                            <td>{{ !empty($testType->pivot->type) ? $testType->pivot->type : '-' }}</td>
                                            <td>{{ !empty($testType->pivot->unit) ? $testType->pivot->unit : '-' }}</td>
                                            <td>{{ !empty($testType->pivot->shade) ? $testType->pivot->shade : '-' }}</td>
                                            <td>{{ \Carbon\Carbon::parse($testType->pivot->created_at)->format('M j, Y') }}
                                            </td>
                                            <td>{{ $history->lab_test_progress }}</td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if (auth('admin')->user()->can('patient.view'))
                                                        <a class="btn btn-outline-primary square-btn"
                                                            href="{{ route('admin.patient.view', [$testType['id']]) }}">
                                                            <i class="tio tio-visible"></i>
                                                        </a>
                                                    @endif
                                                    @if (auth('admin')->user()->can('patient.edit'))
                                                        <a class="btn btn-outline-primary square-btn"
                                                            href="{{ route('admin.patient.edit', [$testType['id']]) }}">
                                                            <i class="tio tio-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (auth('admin')->user()->can('patient.delete'))
                                                        <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                            onclick="form_alert('patient-{{ $testType['id'] }}','{{ \App\CentralLogics\translate('Want to delete this patient ?') }}')"><i
                                                                class="tio tio-delete"></i></a>
                                                    @endif
                                                </div>
                                                <form action="{{ route('admin.patient.delete', [$testType['id']]) }}"
                                                    method="post" id="patient-{{ $testType['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $medicalHistories->links() !!}
                        </div>
                    </div>
                    @if ($totalTestTypes == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin/svg/illustrations/sorry.svg') }}"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No test types to show') }}</p>
                        </div>
                    @endif

                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
        $('#search-form').on('submit', function() {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{ route('admin.patient.search') }}',
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
