@extends('layouts.admin.app')

@section('title', translate('Assessment Categories'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/category.png') }}" alt="">
                {{ translate('Assessment Categories') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <form action="{{ route('admin.assessment-categories.store') }}" method="post">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Name') }}<span
                                            class="text-danger">*</span></label>
                                    <input type="text" name="name" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Category Type') }}<span
                                            class="text-danger">*</span></label>
                                    <select name="category_type" class="form-control js-select2-custom" required>
                                        <option value="" selected disabled>{{ translate('Select Category Type') }}
                                        </option>
                                        <option value="Vital Sign">{{ translate('Vital Sign') }}</option>
                                        <option value="Physical Tests">{{ translate('Physical Tests') }}</option>
                                        <option value="Labour Followup">{{ translate('Labour Followup') }}</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <label class="input-label">{{ translate('Unit') }}</label>
                                    <select name="unit_id" class="form-control js-select2-custom">
                                        <option value="" selected disabled>{{ translate('Select Unit') }}</option>
                                        @foreach ($units as $unit)
                                            <option value="{{ $unit->id }}">{{ $unit->name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="d-flex justify-content-end gap-3">
                                <button type="submit" class="btn btn-primary">{{ translate('Submit') }}</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <div class="row g-2">
            <div class="col-12">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title">
                            <span class="card-header-icon">
                                <i class="tio-view-list"></i>
                            </span>
                            <span>{{ translate('Category List') }}</span>
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table
                                class="table table-hover table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                                <thead class="thead-light">
                                    <tr>
                                        <th>{{ translate('ID') }}</th>
                                        <th>{{ translate('Name') }}</th>
                                        <th>{{ translate('Category Type') }}</th>
                                        <th>{{ translate('Unit') }}</th>
                                        <th class="text-center">{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->category_type }}</td>
                                            <td>{{ $category->unit ? $category->unit->name : 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if (auth('admin')->user()->can('assessment-categories.edit'))
                                                        <a class="btn btn-outline-primary square-btn"
                                                            href="{{ route('admin.assessment-categories.edit', [$category->id]) }}">
                                                            <i class="tio tio-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (auth('admin')->user()->can('assessment-categories.delete'))
                                                        <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                            onclick="form_alert('category-{{ $category->id }}','{{ translate('Want to delete this category ?') }}')">
                                                            <i class="tio tio-delete"></i>
                                                        </a>
                                                    @endif
                                                </div>
                                                <form
                                                    action="{{ route('admin.assessment-categories.delete', [$category->id]) }}"
                                                    method="post" id="category-{{ $category->id }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('script_2')
    <script>
        $(document).on('click', '.delete-data', function() {
            let id = $(this).data('id');
            Swal.fire({
                title: '{{ translate('Are you sure?') }}',
                text: "{{ translate("You won't be able to revert this!") }}",
                showCancelButton: true,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: '{{ translate('Yes, delete it!') }}'
            }).then((result) => {
                if (result.isConfirmed) {
                    $.ajax({
                        url: '{{ url('/') }}/admin/assessment-categories/delete/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            toastr.success(
                                '{{ translate('Category deleted successfully!') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
