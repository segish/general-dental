@extends('layouts.admin.app')

@section('title', translate('Inclinic Items List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/medicine.png') }}" alt="">
                {{ translate('Inclinic Items List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $medicines->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                                <form action="{{ url()->current() }}" method="GET">
                                    <div class="input-group">
                                        <input id="datatableSearch_" type="search" name="search" class="form-control"
                                            placeholder="{{ translate('Search by name or type...') }}" aria-label="Search"
                                            value="{{ $search ?? '' }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary">
                                                {{ translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('emergency-medicines.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.emergency-medicines.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ translate('Add New Item') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless table-thead-bordered">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ translate('SL') }}</th>
                                    <th>{{ translate('Name') }}</th>
                                    <th>{{ translate('Description') }}</th>
                                    <th>{{ translate('Unit') }}</th>
                                    <th>{{ translate('Payment Timing') }}</th>
                                    <th>{{ translate('Item Type') }}</th>
                                    <th>{{ translate('Category') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicines as $key => $medicine)
                                    <tr>
                                        <td>{{ $medicines->firstItem() + $key }}</td>
                                        <td>{{ $medicine->name }}</td>
                                        <td>{{ Str::limit($medicine->description, 50) }}</td>
                                        <td>{{ $medicine->unit ? $medicine->unit->name : 'N/A' }}</td>
                                        <td>{{ ucfirst($medicine->payment_timing) }}</td>
                                        <td>{{ ucfirst($medicine->item_type) }}</td>
                                        <td>{{ $medicine->category ? $medicine->category->name : 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('emergency-medicines.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.emergency-medicines.edit', [$medicine->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('emergency-medicines.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('medicine-{{ $medicine->id }}','{{ translate('Want to delete this medicine ?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form
                                                action="{{ route('admin.emergency-medicines.delete', [$medicine->id]) }}"
                                                method="post" id="medicine-{{ $medicine->id }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="d-flex justify-content-end mt-3">
                        {!! $medicines->links() !!}
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
                        url: '{{ url('/') }}/admin/emergency-medicines/delete/' + id,
                        type: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}'
                        },
                        success: function() {
                            toastr.success(
                                '{{ translate('Medicine deleted successfully!') }}');
                            location.reload();
                        }
                    });
                }
            })
        });
    </script>
@endpush
