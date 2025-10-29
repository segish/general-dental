@extends('layouts.admin.app')

@section('title', translate('Test Type'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('Test Type List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $tests->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-8 col-sm-12 col-md-12">
                                <form action="{{ url()->current() }}" method="GET" class="row g-2">
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ translate('Search by any term...') }}" aria-label="Search"
                                                value="{{ $search }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <select name="category" class="form-control js-select2-custom">
                                            <option value="">{{ translate('All Categories') }}</option>
                                            @foreach ($testCategories as $testCategory)
                                                <option value="{{ $testCategory->id }}"
                                                    {{ $category == $testCategory->id ? 'selected' : '' }}>
                                                    {{ $testCategory->name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-3 col-md-3 col-sm-6">
                                        <select name="status" class="form-control">
                                            <option value="">{{ translate('All Status') }}</option>
                                            <option value="1" {{ $status == '1' ? 'selected' : '' }}>
                                                {{ translate('Active') }}</option>
                                            <option value="0" {{ $status == '0' ? 'selected' : '' }}>
                                                {{ translate('Inactive') }}</option>
                                        </select>
                                    </div>
                                    <div class="col-lg-2 col-md-2 col-sm-12">
                                        <div class="d-flex gap-1">
                                            <button type="submit" class="btn btn-primary flex-fill">
                                                {{ \App\CentralLogics\translate('Filter') }}
                                            </button>
                                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                                {{ \App\CentralLogics\translate('Clear') }}
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('test.add-new'))
                                <div
                                    class="col-lg-4 col-sm-12 col-md-12 d-flex justify-content-lg-end justify-content-sm-start">
                                    <a href="{{ route('admin.test.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Test Type') }}
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
                                    <th>{{ \App\CentralLogics\translate('Test Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Cost') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Category') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Time Taken') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Is Active') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($tests as $key => $test)
                                    <tr>
                                        <td>{{ $tests->firstitem() + $key }}</td>
                                        <td>{{ $test->test_name }}</td>
                                        <td>{{ $test->cost }}</td>
                                        <td>{{ $test->testCategory->name }}</td>
                                        <td>
                                            @if ($test->time_taken_hour > 0 && $test->time_taken_min > 0)
                                                {{ $test->time_taken_hour }}
                                                hour{{ $test->time_taken_hour > 1 ? 's' : '' }}
                                                and {{ $test->time_taken_min }}
                                                min{{ $test->time_taken_min > 1 ? 's' : '' }}
                                            @elseif ($test->time_taken_hour > 0)
                                                {{ $test->time_taken_hour }}
                                                hour{{ $test->time_taken_hour > 1 ? 's' : '' }}
                                            @elseif ($test->time_taken_min > 0)
                                                {{ $test->time_taken_min }} min{{ $test->time_taken_min > 1 ? 's' : '' }}
                                            @else
                                                N/A
                                            @endif
                                        </td>
                                        <td>
                                            @if ($test->is_active == 1)
                                                <span
                                                    class="badge bg-success">{{ \App\CentralLogics\translate('Active') }}</span>
                                            @else
                                                <span
                                                    class="badge bg-danger">{{ \App\CentralLogics\translate('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('test.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.test.edit', [$test->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('test.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('test-{{ $test->id }}','{{ \App\CentralLogics\translate('Want to delete this test type ?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.test.delete', [$test->id]) }}" method="post"
                                                id="test-{{ $test->id }}">
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
                            {!! $tests->links() !!}
                        </div>
                    </div>
                    @if (count($tests) == 0)
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
