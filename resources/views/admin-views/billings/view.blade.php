@extends('layouts.admin.app')

@section('title', translate('billings List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
       
        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-4 col-sm-8 col-md-6">
                            
                            </div>
                           
                        </div>
                    </div>

                    <div class="modal-body row" style="font-family: emoji;">
                        <div class="col-md-12">
                            <center>
                                <button 
                                type="button" class="btn btn-primary non-printable"
                                    onclick="printDiv('printableArea')"
                                    value="{{translate('Print')}}"
                                >
                                <i class="nav-icon tio-print"></i>
                                Print
                                </button>
                                <a href="{{url()->previous()}}"
                                class="btn btn-danger non-printable">{{\App\CentralLogics\translate('Back')}}</a>
                            </center>
                            <hr class="non-printable">
                        </div>
                        <div class="row" id="printableArea" style="margin: auto;">
                            <div class="col-md-12 d-flex justify-content-center">
                                @include('admin-views.billings.invoices.invoice3') 
                            </div>
                        </div>
                        
        
                    </div>
                </div>
                <!-- End Card -->
            </div>
        </div>
    </div>

@endsection

@push('script_2')
    <script>
          function printDiv(divName) {
            var printContents = document.getElementById(divName).innerHTML;
            var originalContents = document.body.innerHTML;
            document.body.innerHTML = printContents;
            window.print();
            document.body.innerHTML = originalContents;
            location.reload();
        }
        $('#search-form').on('submit', function () {
            var formData = new FormData(this);
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
            $.post({
                url: '{{route('admin.invoice.search')}}',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                beforeSend: function () {
                    $('#loading').show();
                },
                success: function (data) {
                    $('#set-rows').html(data.view);
                    $('.page-area').hide();
                },
                complete: function () {
                    $('#loading').hide();
                },
            });
        });
    </script>
@endpush
