@extends('layouts.admin.app')

@section('title', translate('Users List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/user.png') }}" alt="">
                {{ \App\CentralLogics\translate('users_list') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $users->total() }}</span>
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
                                            placeholder="{{ translate('Search by User Name') }}" aria-label="Search"
                                            value="{{ $search }}" required autocomplete="off">
                                        <div class="input-group-append">
                                            <button type="submit"
                                                class="btn btn-primary">{{ \App\CentralLogics\translate('search') }}
                                            </button>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('user.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.user.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('add_new_user') }}
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
                                    <th>{{ \App\CentralLogics\translate('first_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('last_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('phone') }}</th>
                                    <th>{{ \App\CentralLogics\translate('email') }}</th>
                                    <th>{{ \App\CentralLogics\translate('role') }}</th>
                                    <th>{{ \App\CentralLogics\translate('status') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($users as $key => $user)
                                    <tr>
                                        <td>{{ $users->firstitem() + $key }}</td>

                                        <td>{{ $user['f_name'] }}</td>
                                        <td>{{ $user['l_name'] }}</td>
                                        <td>{{ $user['phone'] }}</td>
                                        <td>{{ $user['email'] }}</td>
                                        <td>
                                            @if (!empty($user->getRoleNames()))
                                                @foreach ($user->getRoleNames() as $v)
                                                    <label class="badge badge-success">{{ $v }}</label>
                                                @endforeach
                                            @endif
                                        </td>
                                        
                                        <td>
                                            @if ($user['status'])
                                                    <label class="badge badge-success">active</label>
                                                    @else
                                                    <label class="badge badge-danger">inactive</label>
                                            @endif
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('user.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.user.edit', [$user['id']]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('user.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('user-{{ $user['id'] }}','{{ \App\CentralLogics\translate('Want to delete this User ?') }}')"><i
                                                            class="tio tio-delete"></i></a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.user.delete', [$user['id']]) }}" method="post"
                                                id="user-{{ $user['id'] }}">
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
                            {!! $users->links() !!}
                        </div>
                    </div>
                    @if (count($users) == 0)
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
                url: '{{ route('admin.user.search') }}',
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
