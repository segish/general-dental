@extends('layouts.admin.app')

@section('title', translate('Condition Categories List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assetsadmin/img/icons/category.png') }}" alt="">
                {{ translate('Condition Categories') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="row gy-2 align-items-center">
                        <div class="col-lg-4 col-sm-8 col-md-6">
                            <form action="{{ url()->current() }}" method="GET">
                                <div class="input-group">
                                    <input id="datatableSearch_" type="search" name="search" class="form-control"
                                        placeholder="{{ translate('Search by name, type, desc...') }}" aria-label="Search"
                                        value="{{ $search }}" autocomplete="off">
                                    <div class="input-group-append">
                                        <button type="submit"
                                            class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                        @if (auth('admin')->user()->can('condition_category.add-new'))
                            <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                <a href="{{ route('admin.condition_category.add-new') }}" class="btn btn-primary">
                                    <i class="tio-add"></i>
                                    {{ \App\CentralLogics\translate('add_new_condition_category') }}
                                </a>
                            </div>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ translate('ID') }}</th>
                                        <th>{{ translate('Name') }}</th>
                                        <th>{{ translate('Type') }}</th>
                                        <th>{{ translate('Description') }}</th>
                                        <th>{{ translate('Actions') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->type }}</td>
                                            <td>{{ Str::limit($category->description, 50) ?? 'N/A' }}</td>
                                            {{-- <td>
                                                <div class="btn--container justify-content-center">
                                                    <a href="{{ route('admin.condition_category.edit', $category->id) }}"
                                                        class="btn btn-sm btn-primary">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.condition_category.delete', $category->id) }}"
                                                        method="post"
                                                        onsubmit="return confirm('{{ translate('Are you sure you want to delete this category?') }}')">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="tio-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td> --}}

                                            <td>
                                                <div class="d-flex gap-2 justify-content-center">
                                                    @if (auth('admin')->user()->can('condition_category.edit'))
                                                        <a class="btn btn-outline-primary square-btn"
                                                            href="{{ route('admin.condition_category.edit', $category->id) }}">
                                                            <i class="tio tio-edit"></i>
                                                        </a>
                                                    @endif
                                                    @if (auth('admin')->user()->can('condition_category.delete'))
                                                        <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                            onclick="form_alert('category-{{ $category['id'] }}','{{ \App\CentralLogics\translate('Want to delete this category ?') }}')"><i
                                                                class="tio tio-delete"></i></a>
                                                    @endif
                                                </div>
                                                <form
                                                    action="{{ route('admin.condition_category.delete', [$category['id']]) }}"
                                                    method="post" id="category-{{ $category['id'] }}">
                                                    @csrf @method('delete')
                                                </form>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-center">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
