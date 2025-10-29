@extends('layouts.admin.app')

@section('title', translate('Medical Documents List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">

                {{ \App\CentralLogics\translate('Medical Documents List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $medicalDocuments->total() }}</span>
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
                            @if (auth('admin')->user()->can('medical_document.add-new'))
                                <div class="col-lg-8 col-sm-4 col-md-6 d-flex justify-content-sm-end">
                                    <a href="{{ route('admin.medical_document.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Document') }}
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
                                    <th>{{ \App\CentralLogics\translate('Full Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Filled By') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Language') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('Action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($medicalDocuments as $key => $consentForm)
                                    <tr>
                                        <td>{{ $medicalDocuments->firstitem() + $key }}</td>
                                        <td>{{ $consentForm->visit->patient->full_name }}</td>
                                        <td>{{ $consentForm->doctor->full_name }}</td>
                                        <td>{{ $consentForm->type }}</td>
                                        <td>{{ $consentForm->language }}</td>
                                        <td>{{ \Carbon\Carbon::parse($consentForm->date)->format('M j, Y') }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('medical_document.pdf'))
                                                    <a href="javascript:void(0);" class="btn btn-outline-primary square-btn"
                                                        data-toggle="modal" data-target="#pdfModal"
                                                        onclick="loadPdf('{{ route('admin.medical_document.pdf', $consentForm->id) }}')">
                                                        <i class="tio tio-visible"></i>
                                                    </a>

                                                    <!-- Modal for displaying PDF -->
                                                    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog"
                                                        aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pdfModalLabel">
                                                                        Consent Form PDF</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <iframe id="pdfIframe" width="100%"
                                                                        height="500px"></iframe>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <!-- Button to download PDF -->
                                                                    <a href="{{ route('admin.medical_document.download', $consentForm->id) }}"
                                                                        class="btn btn-success">Download PDF</a>
                                                                    <button type="button" class="btn btn-secondary"
                                                                        data-dismiss="modal">Close</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
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
                            {!! $medicalDocuments->links() !!}
                        </div>
                    </div>
                    @if (count($medicalDocuments) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3"
                                src="{{ asset(config('app.asset_path') . '/admin/svg/illustrations/sorry.svg') }}"
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
        function loadPdf(pdfUrl) {
            document.getElementById('pdfIframe').src = pdfUrl;
        }
    </script>
@endpush
