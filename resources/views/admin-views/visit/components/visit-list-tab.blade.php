<!-- Visit List Tab Content -->
<div class="tab-pane fade  {{ request()->get('active') == 'visit-list' ? 'show active' : '' }}" id="visit-list" role="tabpanel" aria-labelledby="visit-list-tab">
    <div class="px-20 py-3">
        <div class="row gy-2 align-items-center">
            <div class="col-lg-4 col-sm-8 col-md-6">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <input type="search" name="search" class="form-control"
                            placeholder="{{ translate('Search by name or visit type or date') }}" aria-label="Search"
                            value="{{ $search }}" autocomplete="off">
                        <div class="input-group-append">
                            <button type="submit" class="btn btn-primary">
                                {{ \App\CentralLogics\translate('search') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
            <div class="col-lg-3 col-sm-8 col-md-6">
                <form action="{{ url()->current() }}" method="GET">
                    <div class="input-group">
                        <select name="visit_type" class="form-control" onchange="this.form.submit()">
                            <option value="">{{ \App\CentralLogics\translate('All Visit Types') }}
                            </option>
                            <option value="IPD" {{ $visit_type == 'IPD' ? 'selected' : '' }}>
                                {{ \App\CentralLogics\translate('IPD') }}</option>
                            <option value="OPD" {{ $visit_type == 'OPD' ? 'selected' : '' }}>
                                {{ \App\CentralLogics\translate('OPD') }}</option>
                        </select>
                        @if ($search)
                            <input type="hidden" name="search" value="{{ $search }}">
                        @endif
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="table-responsive datatable-custom">
        <table class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
            <thead class="thead-light">
                <tr>
                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                    <th>{{ \App\CentralLogics\translate('Patient Name') }}</th>
                    <th>{{ \App\CentralLogics\translate('Doctor Name') }}</th>
                    <th>{{ \App\CentralLogics\translate('Service Category') }}</th>
                    <th>{{ \App\CentralLogics\translate('Visit Type') }}</th>
                    <th>{{ \App\CentralLogics\translate('Visit Date') }}</th>
                    <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                </tr>
            </thead>
            <tbody id="set-rows">
                @foreach ($visits as $key => $visit)
                    <tr>
                        <td>{{ $visits->firstitem() + $key }}</td>
                        <td>{{ $visit->patient->full_name }}</td>
                        <td>{{ $visit->doctor->full_name ?? 'N/A' }}</td>
                        <td>{{ $visit->serviceCategory->name }}</td>
                        <td>{{ $visit->visit_type }}</td>
                        <td>{{ $visit->formatted_visit_date }}</td>
                        <td>
                            <div class="d-flex gap-2 justify-content-center">
                                @if (auth('admin')->user()->can('visit.view'))
                                    <a class="btn btn-outline-primary square-btn"
                                        href="{{ route('admin.patient.view', [$visit->patient->id]) . '?active=' . $visit->id }}">
                                        <i class="tio tio-edit"></i>
                                    </a>
                                @endif
                                @if (auth('admin')->user()->can('visit.delete'))
                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                        onclick="form_alert('visit-{{ $visit->id }}','{{ \App\CentralLogics\translate('Want to delete this visit?') }}')">
                                        <i class="tio tio-delete"></i>
                                    </a>
                                @endif
                            </div>
                            <form action="{{ route('admin.visit.delete', [$visit['id']]) }}" method="post"
                                id="visit-{{ $visit['id'] }}">
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
            {!! $visits->links() !!}
        </div>
    </div>
    @if (count($visits) == 0)
        <div class="text-center p-4">
            <img class="mb-3" src="{{ asset(config('app.asset_path') . '/admin/svg/illustrations/sorry.svg') }}"
                alt="Image Description" style="width: 7rem;">
            <p class="mb-0">{{ translate('No data to show') }}</p>
        </div>
    @endif
</div>
