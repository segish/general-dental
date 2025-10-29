@extends('layouts.admin.app')

@section('title', translate('Medical Certification List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">

                {{ \App\CentralLogics\translate('Medical Certification List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $medicalCertifications->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('full_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Filled By') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Rest Required') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Date') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>

                            <tbody id="set-rows">
                                @foreach ($medicalCertifications as $key => $medicalCertification)
                                    <tr>
                                        <td>{{ $medicalCertifications->firstitem() + $key }}</td>
                                        <td>{{ $medicalCertification->visit->patient->full_name }}</td>
                                        <td>{{ $medicalCertification->doctor->full_name }}</td>
                                        <td>{{ $medicalCertification->rest_required }}</td>
                                        <td>{{ \Carbon\Carbon::parse($medicalCertification->date)->format('M j, Y') }}
                                        </td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('medical_certification.pdf'))
                                                    <a href="javascript:void(0);" class="btn btn-outline-primary square-btn"
                                                        data-toggle="modal" data-target="#pdfModal"
                                                        onclick="loadPdf('{{ route('admin.medical_certification.pdf', $medicalCertification->id) }}')">
                                                        <i class="tio tio-visible"></i>
                                                    </a>

                                                    <!-- Modal for displaying PDF -->
                                                    <div class="modal fade" id="pdfModal" tabindex="-1" role="dialog"
                                                        aria-labelledby="pdfModalLabel" aria-hidden="true">
                                                        <div class="modal-dialog modal-lg" role="document">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="pdfModalLabel">
                                                                        Medical Certificate PDF</h5>
                                                                    <button type="button" class="close"
                                                                        data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body">
                                                                    <!-- Empty iframe that will load the PDF when the modal opens -->
                                                                    <iframe id="pdfIframe" width="100%"
                                                                        height="500px"></iframe>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <!-- Button to download PDF -->
                                                                    <a href="{{ route('admin.medical_certification.download', $medicalCertification->id) }}"
                                                                        class="btn btn-success">Download
                                                                        PDF</a>
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
                            {!! $medicalCertifications->links() !!}
                        </div>
                    </div>
                    @if (count($medicalCertifications) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{asset('/assets/admin')}}/svg/illustrations/sorry.svg" alt="Image Description" style="width: 7rem;">
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
            // Load the PDF into the iframe when the modal is triggered
            document.getElementById('pdfIframe').src = pdfUrl;
        }
    </script>
@endpush
