@extends('layouts.admin.app')

@section('title', translate('Medicine List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/medicine.png') }}"
                    alt="">
                {{ translate('Medicine List') }}
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
                            @if (auth('admin')->user()->can('patient.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.medicines.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Medicine') }}
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
                                    <th>{{ translate('Code') }}</th>
                                    <th>{{ translate('Medicine Name') }}</th>
                                    <th>{{ translate('Category') }}</th>
                                    <th>{{ translate('Description') }}</th>
                                    <th class="text-center">{{ translate('Action') }}</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($medicines as $key => $medicine)
                                    <tr>
                                        <td>{{ $medicines->firstItem() + $key }}</td>
                                        <td>{{ $medicine->code }}</td>
                                        <td>{{ $medicine->name }}</td>
                                        <td>{{ $medicine->category->name ?? 'N/A' }}</td>
                                        <td>{{ $medicine->description ?? 'N/A' }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">

                                                @if (auth('admin')->user()->can('medicines.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.medicines.edit', [$medicine['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('medicines.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('patient-{{ $medicine['id'] }}','{{ \App\CentralLogics\translate('Want to delete this patient ?') }}')"><i
                                                            class="tio tio-delete"></i></a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.medicines.delete', [$medicine['id']]) }}"
                                                method="post" id="patient-{{ $medicine['id'] }}">
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

    <script>
        function deleteMedicine(id) {
            if (confirm("{{ translate('Are you sure you want to delete this medicine?') }}")) {
                document.getElementById('delete-form-' + id).submit();
            }
        }
    </script>
@endsection
