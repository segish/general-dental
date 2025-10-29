@extends('layouts.admin.app')

@section('title', translate('Radiology'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('Radiology List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $radiologys->total() }}</span>
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
                                            placeholder="{{ translate('Search by any term...') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('radiology.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.radiology.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Radiology') }}
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
                                    <th>{{ \App\CentralLogics\translate('Radiology Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Cost') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Time Taken') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Is Inhouse') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Is Active') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($radiologys as $key => $radiology)
                                    <tr>
                                        <td>{{ $radiologys->firstitem() + $key }}</td>
                                        <td>{{ $radiology->radiology_name }}</td>
                                        <td>{{ $radiology->cost }}</td>
                                        <td>
                                            @if ($radiology->time_taken_hour > 0 && $radiology->time_taken_min > 0)
                                                {{ $radiology->time_taken_hour }}
                                                hour{{ $radiology->time_taken_hour > 1 ? 's' : '' }}
                                                and {{ $radiology->time_taken_min }}
                                                min{{ $radiology->time_taken_min > 1 ? 's' : '' }}
                                            @elseif ($radiology->time_taken_hour > 0)
                                                {{ $radiology->time_taken_hour }}
                                                hour{{ $radiology->time_taken_hour > 1 ? 's' : '' }}
                                            @elseif ($radiology->time_taken_min > 0)
                                                {{ $radiology->time_taken_min }}
                                                min{{ $radiology->time_taken_min > 1 ? 's' : '' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($radiology->is_inhouse == 1)
                                                <span
                                                    class="badge bg-success">{{ \App\CentralLogics\translate('Yes') }}</span>
                                            @else
                                                <span
                                                    class="badge bg-danger">{{ \App\CentralLogics\translate('No') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($radiology->is_active == 1)
                                                <span
                                                    class="badge bg-success">{{ \App\CentralLogics\translate('Active') }}</span>
                                            @else
                                                <span
                                                    class="badge bg-danger">{{ \App\CentralLogics\translate('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('radiology.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.radiology.edit', [$radiology->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('radiology.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('radiology-{{ $radiology->id }}','{{ \App\CentralLogics\translate('Want to delete this radiology?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.radiology.delete', [$radiology->id]) }}"
                                                method="post" id="radiology-{{ $radiology->id }}">
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
                            {!! $radiologys->links() !!}
                        </div>
                    </div>
                    @if (count($radiologys) == 0)
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

<style>
    .description-cell {
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        max-width: 150px;
        /* Adjust as needed */
    }
</style>
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
                url: '{{ route('admin.radiology.search') }}',
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
