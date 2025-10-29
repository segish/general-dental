@extends('layouts.admin.app')

@section('title', translate('Medical Conditions List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                {{ translate('Medical Conditions') }}
            </h2>
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
                                            placeholder="{{ translate('Search by name, code, desc...') }}" aria-label="Search"
                                            value="{{ $search }}" autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('medical_condition.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.medical_condition.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('add_new_medical_condition') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ translate('ID') }}</th>
                                        <th>{{ translate('Name') }}</th>
                                        <th>{{ translate('Code') }}</th>
                                        <th>{{ translate('Category') }}</th>
                                        <th>{{ translate('Description') }}</th>
                                        <th>{{ translate('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($medical_conditions as $condition)
                                        <tr>
                                            <td>{{ $condition->id }}</td>
                                            <td>{{ $condition->name }}</td>
                                            <td>{{ $condition->code ?? 'N/A' }}</td>
                                            <td>{{ $condition->category->name ?? 'N/A' }}</td>
                                            <td>{{ Str::limit($condition->description, 50) ?? 'N/A' }}</td>
                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if (auth('admin')->user()->can('medical_condition.edit'))
                                                        <a href="{{ route('admin.medical_condition.edit', $condition->id) }}"
                                                            class="btn btn-outline-primary square-btn">
                                                            <i class="tio tio-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (auth('admin')->user()->can('medical_condition.delete'))
                                                        <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                            onclick="form_alert('condition-{{ $condition['id'] }}','{{ \App\CentralLogics\translate('Want to delete this condition ?') }}')"><i
                                                                class="tio tio-delete"></i></a>
                                                    @endif
                                                </div>
                                                <form
                                                    action="{{ route('admin.medical_condition.delete', [$condition['id']]) }}"
                                                    method="post" id="condition-{{ $condition['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $medical_conditions->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
