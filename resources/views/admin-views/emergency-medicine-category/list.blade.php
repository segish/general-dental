@extends('layouts.admin.app')

@section('title', translate('Inclinic Item Categories'))

@section('content')
    <div class="content container-fluid">
        <div class="mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/public/assets/admin/img/icons/category.png') }}" alt="">
                {{ translate('Inclinic Item Categories') }}
            </h2>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>{{ translate('ID') }}</th>
                                        <th>{{ translate('Name') }}</th>
                                        <th>{{ translate('Description') }}</th>
                                        <th>{{ translate('Action') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($categories as $category)
                                        <tr>
                                            <td>{{ $category->id }}</td>
                                            <td>{{ $category->name }}</td>
                                            <td>{{ $category->description }}</td>
                                            <td>
                                                <div class="d-flex gap-2">
                                                    <a href="{{ route('admin.emergency_medicine_categories.edit', $category->id) }}"
                                                        class="btn btn-sm btn-outline-primary">
                                                        <i class="tio-edit"></i>
                                                    </a>
                                                    <form
                                                        action="{{ route('admin.emergency_medicine_categories.delete', $category->id) }}"
                                                        method="post">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-outline-danger"
                                                            onclick="return confirm('{{ translate('Are you sure?') }}')">
                                                            <i class="tio-delete"></i>
                                                        </button>
                                                    </form>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end">
                            {{ $categories->links() }}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
