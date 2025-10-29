@extends('layouts.admin.app')

@section('title', translate('About us'))

@push('css_or_js')
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-4">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset(config('app.asset_path') . '/admin/img/icons/pages.png') }}" alt="">
                {{ \App\CentralLogics\translate('pages') }}
            </h2>
        </div>

        <div class="inline-page-menu mb-4">
            @include('admin-views.business-settings.partial.page-nav')
        </div>

        <div class="card">
            <div class="card-body">
                <form action="{{ route('admin.business-settings.about-us') }}" method="post" id="tnc-form">
                    @csrf
                    <div class="form-group">
                        <textarea class="ckeditor form-control" name="about_us">{!! $data['value'] !!}</textarea>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="submit" class="btn btn-primary">{{ translate('submit') }}</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function() {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
