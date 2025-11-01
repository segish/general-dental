@extends('layouts.admin.app')

@section('title', translate('Medical Record Fields List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ \App\CentralLogics\translate('Medical Record Fields List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $fields->total() }}</span>
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
                                            placeholder="{{ translate('Search by name, code, or type') }}"
                                            aria-label="Search" value="{{ $search ?? '' }}" autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('Search') }}</button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                <a href="{{ route('admin.medical_record_field.add-new') }}" class="btn btn-primary">
                                    <i class="tio-add"></i>
                                    {{ \App\CentralLogics\translate('Add New Field') }}
                                </a>
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Short Code') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Field Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Options Count') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Required') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Order') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($fields as $key => $field)
                                    <tr>
                                        <td>{{ $fields->firstitem() + $key }}</td>
                                        <td>{{ $field->name }}</td>
                                        <td><code>{{ $field->short_code }}</code></td>
                                        <td>
                                            <span class="badge badge-info">{{ ucfirst($field->field_type) }}</span>
                                            @if ($field->is_multiple)
                                                <span class="badge badge-secondary">Multiple</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if (in_array($field->field_type, ['select', 'multiselect', 'checkbox']))
                                                <span
                                                    class="badge badge-soft-primary">{{ $field->options_count ?? 0 }}</span>
                                            @else
                                                <span class="text-muted">-</span>
                                            @endif
                                        </td>
                                        <td>
                                            @if ($field->is_required)
                                                <span class="badge badge-danger">{{ translate('Yes') }}</span>
                                            @else
                                                <span class="badge badge-soft-secondary">{{ translate('No') }}</span>
                                            @endif
                                        </td>
                                        <td>{{ $field->order }}</td>
                                        <td>
                                            @if ($field->status)
                                                <span class="badge badge-success">{{ translate('Active') }}</span>
                                            @else
                                                <span class="badge badge-danger">{{ translate('Inactive') }}</span>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('medical_record_field.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.medical_record_field.edit', [$field->id]) }}"
                                                        title="{{ translate('Edit') }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('medical_record_field.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('field-{{ $field->id }}','{{ \App\CentralLogics\translate('Want to delete this field?') }}')"
                                                        title="{{ translate('Delete') }}">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.medical_record_field.delete', [$field->id]) }}"
                                                method="post" id="field-{{ $field->id }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $fields->links() !!}
                        </div>
                    </div>
                    @if (count($fields) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
