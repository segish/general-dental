@extends('layouts.admin.app')

@section('title', translate('beds List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assets/admin/img/icons/bed.png') }}" alt="">
                {{ \App\CentralLogics\translate('beds_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $beds->total() }}</span>
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
                                            placeholder="{{ translate('Search by bed Name') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('bed.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.bed.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('add_new_bed') }}
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
                                    <th>{{ \App\CentralLogics\translate('Bed Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Price') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Occupancy Status') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('ward_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($beds as $key => $bed)
                                    <tr>
                                        <td>{{ $beds->firstitem() + $key }}</td>

                                        <td>{{ $bed['bed_number'] }}</td>
                                        <td>{{ $bed['price'] }}</td>
                                        <td>
                                            @if ($bed->occupancy_status == 'cleaning')
                                                <p class="text-warning" style="font-weight: bold">Cleaning</p>
                                            @elseif ($bed->occupancy_status == 'maintenance')
                                                <p class="text-success" style="font-weight: bold">Maintenance</p>
                                            @elseif($bed->occupancy_status == 'normal')
                                                <p>Normal</p>
                                            @else
                                                <p>{{ \App\CentralLogics\translate('-') }}</p>
                                            @endif
                                        </td>
                                        <td>{{ $bed['type'] ?? '' }}</td>
                                        <td>{{ $bed->ward->ward_name }}</td>
                                        <td>
                                            @if ($bed->status == 'available')
                                                <p class="text-success" style="font-weight: bold">
                                                    {{ \App\CentralLogics\translate($bed->status) }}</p>
                                            @elseif ($bed->status == 'occupied')
                                                <p class="text-warning" style="font-weight: bold">
                                                    {{ \App\CentralLogics\translate($bed->status) }}</p>
                                            @else
                                                <p>{{ \App\CentralLogics\translate($bed->status) }}</p>
                                            @endif
                                        </td>

                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('bed.view'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.bed.view', [$bed['id']]) }}">
                                                        <i class="tio tio-visible"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('bed.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.bed.edit', [$bed['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('bed.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('bed-{{ $bed['id'] }}','{{ \App\CentralLogics\translate('Want to delete this bed ?') }}')"><i
                                                            class="tio tio-delete"></i></a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.bed.delete', [$bed['id']]) }}" method="post"
                                                id="bed-{{ $bed['id'] }}">
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
                            {!! $beds->links() !!}
                        </div>
                    </div>
                    @if (count($beds) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('/assets/admin') }}/svg/illustrations/sorry.svg"
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
                url: '{{ route('admin.bed.search') }}',
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
