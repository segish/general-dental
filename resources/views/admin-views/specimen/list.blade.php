@extends('layouts.admin.app')

@section('title', translate('Specimen List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">

                {{ \App\CentralLogics\translate('Specimens_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $specimens->total() }}</span>
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
                                            placeholder="{{ translate('Search by Specimen code') }}" aria-label="Search"
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
                                    <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Specimen Code') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Specimen Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Specimen Origin') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                @foreach ($specimens as $key => $specimen)
                                    <tr>
                                        <td>{{ $specimens->firstitem() + $key }}</td>

                                        <td>{{ $specimen->laboratoryRequest->visit->patient->full_name }}</td>
                                        <td>
                                            {{$specimen->specimen_code}}
                                        </td>
                                        <td>
                                           {{$specimen->specimenType->name}}
                                        </td>
                                        <td>
                                           {{$specimen->specimenOrigin->name}}
                                        </td>
                                        <td>
                                            @if ($specimen->status === 'pending')
                                                <p class="m-0" style="color: #FFC107;">Pending</p>
                                            @elseif ($specimen->status === 'in process')
                                                <p class="m-0" style="color: #17A2B8;">In Process</p>
                                            @elseif ($specimen->status === 'accepted')
                                                <p class="m-0" style="color: #28A745;">Accepted</p>
                                            @elseif ($specimen->status === 'rejected')
                                                <p class="m-0" style="color: #DC3545;">Rejected</p>
                                            @else
                                                <p class="m-0" style="color: #6C757D;">-</p>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('patient.view'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.patient.view', [$specimen->laboratoryRequest->visit->patient->id]) }}">
                                                        <i class="tio tio-visible"></i>
                                                    </a>
                                                @endif
                                                {{-- @if (auth('admin')->user()->can('patient.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('patient-{{ $specimen['id'] }}','{{ \App\CentralLogics\translate('Want to delete this patient ?') }}')"><i
                                                            class="tio tio-delete"></i></a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.patient.delete', [$specimen['id']]) }}"
                                                method="post" id="patient-{{ $specimen['id'] }}">
                                                @csrf @method('delete')
                                            </form> --}}
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $specimens->links() !!}
                        </div>
                    </div>
                    @if (count($specimens) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
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
