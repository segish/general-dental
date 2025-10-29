@extends('layouts.admin.app')

@section('title', translate('Radiology Attribute Details'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <!-- Page Header -->
        <div class="mb-3">
            <div class="d-flex flex-wrap gap-3 justify-content-between align-items-center">
                <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                    <img width="20" src="{{ asset('/assets/admin/img/icons/product.png') }}" alt="">
                    {{ $radiologyAttribute->attribute_name }}
                </h2>
                <a href="{{ url()->previous() }}" class="btn btn-primary">
                    <i class="tio-back-ui"></i> {{ \App\CentralLogics\translate('back') }}
                </a>
            </div>
        </div>
        <!-- End Page Header -->

        <!-- Card -->
        <div class="card mb-3">
            <!-- Body -->
            <div class="card-body">
                <div class="row gy-3">
                    <div class="col-md-6 col-lg-4 text-dark">
                        <h4 class="mb-3 text-capitalize">{{ $radiologyAttribute->attribute_name }}</h4>
                        <div>
                            {{ \App\CentralLogics\translate('Radiology') }}
                            : {{ $radiologyAttribute->radiology->name }}
                        </div>
                        <div>
                            {{ \App\CentralLogics\translate('Default Required') }} :
                            <span>{{ $radiologyAttribute->default_required ? 'Yes' : 'No' }}</span>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-8">
                        <div class="border-md-left pl-md-4 h-100">
                            <h4>{{ \App\CentralLogics\translate('Created At') }} : </h4>
                            <p>{{ $radiologyAttribute->created_at->format('d M Y H:i:s') }}</p>

                            <h4>{{ \App\CentralLogics\translate('Updated At') }} : </h4>
                            <p>{{ $radiologyAttribute->updated_at->format('d M Y H:i:s') }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- End Body -->
        </div>
        <!-- End Card -->
    </div>
@endsection

@push('script_2')
    <script>
        $('.ql-hidden').hide()
    </script>
@endpush
