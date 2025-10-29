@extends('layouts.admin.app')

@section('title', translate('Radiology Attribute List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assets/admin/img/icons/test_attribute.png') }}" alt="">
                {{ \App\CentralLogics\translate('Radiology Attribute List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $radiologyAttributes->total() }}</span>
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
                                            placeholder="{{ translate('Search by radiology attribute name') }}"
                                            aria-label="Search" value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('radiology_attribute.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.radiology_attribute.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Radiology Attribute') }}
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
                                    <th>{{ \App\CentralLogics\translate('attribute_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Radiology Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Result Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('default_required') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                @foreach ($radiologyAttributes as $key => $radiologyAttribute)
                                    <tr>
                                        <td>{{ $radiologyAttributes->firstitem() + $key }}</td>
                                        <td>{{ $radiologyAttribute->attribute_name }}</td>
                                        <td>{{ $radiologyAttribute->radiology->radiology_name }}</td>
                                        <td>{{ $radiologyAttribute->result_type == 'paragraph' ? 'paragraph' : 'Short word/Numeric' }}</td>
                                        <td>{{ $radiologyAttribute->default_required ? 'Yes' : 'No' }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('radiology_attribute.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.radiology_attribute.edit', [$radiologyAttribute->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('radiology_attribute.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('radiology_attribute-{{ $radiologyAttribute->id }}','{{ \App\CentralLogics\translate('Want to delete this radiology attribute?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form
                                                action="{{ route('admin.radiology_attribute.delete', [$radiologyAttribute->id]) }}"
                                                method="post" id="radiology_attribute-{{ $radiologyAttribute->id }}">
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
                            {!! $radiologyAttributes->links() !!}
                        </div>
                    </div>
                    @if (count($radiologyAttributes) == 0)
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
                url: '{{ route('admin.radiology_attribute.search') }}',
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
