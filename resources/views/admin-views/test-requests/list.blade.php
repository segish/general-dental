@extends('layouts.admin.app')

@section('title', translate('Test Request'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/testType.png') }}"
                    alt="">
                {{ \App\CentralLogics\translate('Test Request List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $testRequests->total() }}</span>
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
                                            placeholder="{{ translate('Search by testType Name') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('testType.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <button class="btn btn-success rounded text-nowrap" id="add_new_request" type="button"
                                        data-toggle="modal" data-target="#add-request" title="Add Test Request">
                                        <i class="tio-add"></i>
                                        {{ translate('Request') }}
                                    </button>
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
                                    <th>{{ \App\CentralLogics\translate('Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Descripiton') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($testRequests as $key => $testRequest)
                                    <tr>
                                        <td>{{ $testRequests->firstitem() + $key }}</td>
                                        <td>{{ $testRequest->name }}</td>
                                        <td>{{ $testRequest->description }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('testType.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.testType.edit', [$testRequest->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('testType.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('testType-{{ $testRequest->id }}','{{ \App\CentralLogics\translate('Want to delete this testType ?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.testType.delete', [$testRequest->id]) }}"
                                                method="post" id="testType-{{ $testRequest->id }}">
                                                @csrf @method('delete')
                                            </form>
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
                            {!! $testRequests->links() !!}
                        </div>
                    </div>
                    @if (count($testRequests) == 0)
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

    <div class="modal fade" id="add-request" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{ translate('Add New Request') }}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}} action="javascript:" method="post" id="appointment_form"
                        enctype="multipart/form-data">
                        @csrf
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Date') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input type="date" name="date" class="form-control" value="" required="">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Patient') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="patient_id" id="patient_id" class="form-control js-select2-custom"
                                        required>
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        @foreach ($patients as $patient)
                                            <option value="{{ $patient->id }}">{{ $patient->full_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-12">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Test Type') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="sample_type_id" id="sample_type_id"
                                        class="form-control js-select2-custom" required>
                                        @foreach ($testTypes as $testType)
                                            <option value="{{ $testType->id }}">{{ $testType->test_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Sample Type') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="sample_type_id" id="sample_type_id"
                                        class="form-control js-select2-custom" multiple required>
                                        @foreach ($sampleTypes as $sampleType)
                                            <option value="{{ $sampleType->id }}">{{ $sampleType->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Technician') }}<span
                                            class="input-label-secondary text-danger">*</span></label>

                                    <select name="admin_id" id="admin_id" class="form-control js-select2-custom"
                                        required>
                                        <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}
                                        </option>
                                        @foreach ($admins as $admin)
                                            <option value="{{ $admin->id }}">{{ $admin->f_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Referring Institute') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input id="referring_institute" type="text" name="referring_institute"
                                        class="form-control" placeholder="{{ translate('Enter referring institute') }}">
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Hospital Ward') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <input id="hospital_ward" type="text" name="hospital_ward" class="form-control"
                                        placeholder="{{ translate('Enter Hosipital Reward') }}">
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Requested By') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="requested_by" id="requested_by" class="form-control js-select2-custom"
                                        required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select requested by whom') }}</option>
                                        <option value="physican">Physician (In-Clinic)</option>
                                        <option value="self">Self</option>
                                        <option value="other healthcare">Other Healthcare</option>
                                    </select>
                                </div>
                            </div>
                            <div class="col-lg-6">
                                <div class="form-group">
                                    <label class="input-label">{{ translate('Order Status') }}<span
                                            class="input-label-secondary text-danger">*</span></label>
                                    <select name="order_status" id="order_status" class="form-control js-select2-custom"
                                        required>
                                        <option value="" selected disabled>
                                            {{ \App\CentralLogics\translate('Select Order Status') }}</option>
                                        <option value="urgent">Urgent</option>
                                        <option value="routine">Routine</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label"
                                        for="exampleFormControlInput1">{{ \App\CentralLogics\translate('note') }}</label>
                                    <div class="form-group">
                                        <textarea name="notes" class="form-control"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="d-flex justify-content-end">
                            <button type="submit" id=""
                                class="btn btn-primary">{{ translate('Submit') }}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<style>
    .description-cell {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        /* Adjust as needed */
    }
</style>

@push('script')
    <script>
        $(document).ready(function() {
            $('.js-select2-custom').select2({
                allowClear: true,
                width: '100%',
                dropdownAutoWidth: false,
                minimumResultsForSearch: 10;
            });
        });
    </script>
@endpush

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
                url: '{{ route('admin.testType.search') }}',
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
