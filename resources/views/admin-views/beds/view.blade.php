@extends('layouts.admin.app')

@section('title', translate('bed_detail'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="{{asset('/assets/admin/css/tags-input.min.css')}}" rel="stylesheet">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="mb-3 d-md-flex justify-content-between">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{asset('/assets/admin/img/icons/product.png')}}" alt="">
                {{\App\CentralLogics\translate('bed_detail')}}
            </h2>

        </div>


        <div class="row">
            <div class="col-12">

                    @csrf
                    <div id="from_part_2">
                        <div class="card mb-3">
                            <div class="card-body">

                                <div class="row media">
                                    <div class="col-md-7 media-body">
                                        <dl class="row">
                                            <dt class="col-sm-3">Bed Code:</dt>
                                            <dd class="col-sm-9">{{ $bed->code }}</dd>

                                            <dt class="col-sm-3">Occupancy Status:</dt>
                                            <dd class="col-sm-9">{{ $bed->occupancy_status }}</dd>

                                            <dt class="col-sm-3">Type:</dt>
                                            <dd class="col-sm-9">{{ $bed->type }}</dd>

                                            <dt class="col-sm-3">Room Number:</dt>
                                            <dd class="col-sm-9">{{ $bed->room_number }}</dd>

                                            <dt class="col-sm-3">Ward:</dt>
                                            <dd class="col-sm-9">{{ $bed->ward->ward_name }}</dd>

                                        </dl>
                                    </div>

                                    <div class="col-md-5 card card-body">
                                        <h5>Current Status</h5>
                                        @if ($bed->status == 'available')
                                        <p class="text-success" style="font-weight: bold"> {{\App\CentralLogics\translate( $bed->status )}}</p>
                                        @elseif ($bed->status == 'occupied')
                                            <p class="text-info" style="font-weight: bold">{{\App\CentralLogics\translate( $bed->status )}}</p>
                                        @else
                                            <p>{{\App\CentralLogics\translate( $bed->status )}}</p>
                                        @endif

                                        @if ($bed->patient_id)
                                            <h5>Assigned Patient</h5>
                                            <dl class="row">
                                                <dt class="col-sm-6">Name:</dt>
                                                <dd class="col-sm-6">{{ $bed->patient->full_name  }}</dd>
                                                <dt class="col-sm-6">Length of Stay:</dt>
                                                <dd class="col-sm-6"> {{ $bed->calculateStayDays($bed->id) }} days</dd>
                                                <dt class="col-sm-6">Reg_No:</dt>
                                                <dd class="col-sm-6">{{  $bed->patient->registration_no  }}</dd>
                                            </dl>
                                            {{-- <form action="{{ route('admin.bed.dissociate_patient', ['id' => $bed->id]) }}" method="post">
                                                @csrf --}}

                                                <button type="submit" id="add_new_bed_billing" type="button" data-toggle="modal" data-target="#add-bed_billing" title="Add Appointment" class="btn btn-danger" data-length-of-stay="{{ $bed->calculateStayDays($bed->id) }}">
                                                    {{ \App\CentralLogics\translate('Discharge') }}
                                                </button>                                            {{-- </form> --}}

                                        @else
                                            <form action="{{ route('admin.bed.associate_patient', ['id' => $bed->id]) }}" method="post">
                                                @csrf
                                                <div class="form-group">
                                                    <label class="input-label"
                                                        for="patient_id">{{\App\CentralLogics\translate('Assign Patient')}}</label>
                                                        <select name="patient_id" class="form-control js-select2-custom"  required>
                                                            <option value="" selected disabled>{{ \App\CentralLogics\translate('') }}</option>
                                                            @foreach ($patients as $patient)
                                                                <option value="{{$patient->id}}">{{$patient->full_name}}</option>
                                                            @endforeach
                                                        </select>
                                                </div>
                                                <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('Assign')}}</button>

                                            </form>
                                        @endif
                                    </div>

                                </div>

                            </div>
                        </div>
                    </div>
            </div>
        </div>
        @if ($bed->patient_id)
        <div class="row">
            <div class="col-12">
                @csrf
                <div id="from_part_2">
                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row media px-4 py-2"  style="display: flex; align-items:center">
                                <div class=" col-md-6">
                                    <h2 >Admission Note</h4>
                                </div>

                                @php
                                   $adcount=0;
                                   foreach ($notes as $key => $note) {
                                    if($note->admission_note){
                                    $adcount +=1;
                                }

                                   }
                                @endphp
                                @if($notes->count()<=0 || $adcount==0)
                                <div class=" col-md-6" style="display: flex; justify-content:end;">
                                    <button type="button" id="add_new_admission_note"  data-toggle="modal"
                                    data-target="#add-admission_note" title="Add Appointment"
                                    class="btn btn-success" >
                                        <i class="tio-add nav-icon"></i>
                                        {{ \App\CentralLogics\translate('Note') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="row media d-block" >
                                @if ($notes->count() > 0)
                                        @foreach ($notes as $note)
                                            @if ($note->admission_note)
                                                <p class="card-text px-5"> {{ $note->admission_note }}</p>
                                        @endif

                                        @endforeach

                                        @if($adcount==0)
                                        <h4 class="px-4 text-muted" style="text-align: center">Admission Note not added yet!</h4>
                                        @endif
                                @else
                                <h4 class="px-4 text-muted" style="text-align: center">Admission Note not added yet!</h4>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card mb-3">
                        <div class="card-body">
                            <div class="row media px-4 py-2"  style="display: flex; align-items:center">
                                <div class=" col-md-6">
                                    <h2 >Discharge/Summary Note</h4>
                                </div>

                                @php
                                   $adcount=0;
                                   foreach ($notes as $key => $note) {
                                    if($note->summary_discharge_note){
                                    $adcount +=1;
                                }

                                   }
                                @endphp
                                @if($notes->count()<=0 || $adcount==0)
                                <div class=" col-md-6" style="display: flex; justify-content:end;">
                                    <button type="button" id="add_new_discharge_note"  data-toggle="modal"
                                    data-target="#add-discharge_note" title="Add Appointment"
                                    class="btn btn-success" >
                                        <i class="tio-add nav-icon"></i>
                                        {{ \App\CentralLogics\translate(' Note') }}
                                    </button>
                                </div>
                                @endif
                            </div>
                            <div class="row media d-block" >
                                @if ($notes->count() > 0)
                                        @foreach ($notes as $note)
                                            @if ($note->summary_discharge_note)
                                                <p class="card-text px-5"> {{ $note->summary_discharge_note }}</p>
                                        @endif

                                        @endforeach

                                        @if($adcount==0)
                                        <h4 class="px-4 text-muted" style="text-align: center">Summary not added yet!</h4>
                                        @endif
                                @else
                                <h4 class="px-4 text-muted" style="text-align: center">Summary not added yet!</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                    <div class="card mb-3">
                        <div class="card-body">
                            <h2 class="px-3">Patient Daily Notes</h4>
                            <div class="row media" >
                                    <div class=" col-md-10">
                                        <form action="{{ route('admin.bed.view', ['id' => $bed->id]) }}" method="GET">
                                            <div class="input-group">
                                                <input type="date" name="date" class="form-control" value="{{request('date')}}" />
                                                <div class="input-group-append">
                                                    <button type="submit" class="btn btn-primary">{{\App\CentralLogics\translate('search')}}
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" id="add_new_note" data-toggle="modal" data-target="#add-note" title="Add Appointment" class="btn btn-success" data-length-of-stay="{{ $bed->calculateStayDays($bed->id) }}">
                                            <i class="tio-add nav-icon"></i>
                                            {{ \App\CentralLogics\translate('Note') }}
                                        </button>
                                    </div>
                            </div>

                            <div class="row media " >
                                @if ($notes->count() > 0)

                                @php
                                    $adcount2=0;
                                    foreach ($notes as $key => $note) {
                                    if(($note->progress_note_daily || $note->temprature || $note->blood_pressure || $note->respiratory_rate) && !$note->admission_note ){
                                        $adcount2 +=1;
                                    }

                                    }
                                @endphp
                                    <div class="card-deck " style="display: flex; flex-direction:column;">
                                        @foreach ($notes as $note)
                                        @if($adcount2>0)
                                            <div class="card ">
                                                <div class="card-body">
                                                    <h5 class="card-title my-4">{{ $note->created_at->format('M d, Y, g:ia') }}</h5>
                                                    {{-- @if ($note->admission_note)
                                                        <p class="card-text" style="display: flex; flex-direction:column"> <span style="font-weight: bold">Admission Note:</span> {{ $note->admission_note }}</p>
                                                    @endif --}}
                                                    @if ($note->progress_note_daily)
                                                        <p class="card-text"style="display: flex; flex-direction:column"> <span style="font-weight: bold">Daily Note:</span>  {{ $note->progress_note_daily }}</p>
                                                    @endif
                                                    {{-- @if ($note->summary_discharge_note)
                                                        <p class="card-text"style="display: flex; flex-direction:column"> <span style="font-weight: bold">Discharge Note:</span>  {{ $note->summary_discharge_note }}</p>
                                                    @endif --}}
                                                    <div class="row ">
                                                        @if ($note->temperature)
                                                            <p class="card-text col-md-6">Temperature: {{ $note->temperature }}</p>
                                                        @endif
                                                        @if ($note->blood_pressure)
                                                            <p class="card-text col-md-6">Blood Pressure: {{ $note->blood_pressure }}</p>
                                                        @endif
                                                        @if ($note->heart_rate)
                                                            <p class="card-text col-md-6">Heart Rate: {{ $note->heart_rate }}</p>
                                                        @endif
                                                        @if ($note->respiratory_rate)
                                                            <p class="card-text col-md-6">Respiratory Rate: {{ $note->respiratory_rate }}</p>
                                                        @endif
                                                    </div>
                                                    @if ($note->additional_notes)
                                                        <p class="card-text" style="display: flex; flex-direction:column"> <span style="font-weight: bold">Additional Notes:</span> {{ $note->additional_notes }}</p>
                                                    @endif
                                                    <!-- Add other note fields as needed -->
                                                </div>
                                            </div>
                                            @else
                                            <div style="text-align: center">

                                                <h4 class="px-8 text-muted" style="text-align: center">No daily notes available for this patient.</h4>
                                            </div>
                                            @endif
                                        @endforeach
                                    </div>
                                @else
                                    <h4 class="px-8 text-muted" style="text-align: center">No daily notes available for this patient.</h4>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif


    </div>
    @if ($bed->patient_id)
    <div class="modal fade" id="add-bed_billing" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_bed_billing')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form
                    {{-- action="{{route('admin.appointment.store')}}" method="post" id="customer-form" --}}
                    action="javascript:" method="post" id="bed_billing_form"
                    enctype="multipart/form-data"

                    >
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{$bed->patient->id}}">
                        <input type="text" hidden name="bed_id" value="{{$bed->id}}">

                        {{-- <div class="form-group">
                            <label class="input-label" for="test_type">{{\App\CentralLogics\translate('service_type')}}</label>
                            <select name="services" class="form-control js-select2-custom" multiple id="servicesSelect">
                                <option value="" disabled>{{ \App\CentralLogics\translate('') }}</option>
                                @foreach($services as $serice)
                                    <option value="{{ $serice->id }}" data-unit-cost="{{ $serice->cost }}">{{ $serice->service_name }}</option>
                                @endforeach
                            </select>
                        </div> --}}
                        <table class="table" id="serviceCartTable" style="display: none;">
                            <thead>
                            <tr>
                                <th>Service</th>
                                <th>Qty</th>
                                <th>U/Cost</th>
                                <th>T/Cost</th>
                            </tr>
                            </thead>
                            <tbody id="serviceCartBody"></tbody>
                            <tfoot>
                                <tr>
                                    <td colspan="4" style="text-align: right;">
                                        Total: <span id="subTotal">0.00</span> |
                                        Tax ({{\App\Model\BusinessSetting::where('key', 'tax')->first()->value??0}} %) : <span id="tax">0.00</span> |
                                        Grand Total: <span id="grandTotal">0.00</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="4" style="text-align: right; vertical-align: middle;">
                                        Recieved Amount: |
                                        <input type="decimalNumber" name="amount_paid"  class="form-control" placeholder="Amount" required/>
                                    </td>
                                </tr>
                            </tfoot>

                        </table>
                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="exampleFormControlInput1">{{\App\CentralLogics\translate('Additional Note')}}</label>
                                    <div class="form-group">
                                        <textarea name="bed_billing_content"  class="form-control" ></textarea>
                                    </div>
                                </div>

                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($bed->patient_id)
    <div class="modal fade" id="add-note" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_New_note')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="note_form" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{$bed->patient->id}}">
                        <input type="text" hidden name="bed_id" value="{{$bed->id}}">

                        <div class="row pl-2">
                            {{-- <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="admission_note">{{\App\CentralLogics\translate('Admission Note')}}</label>
                                    <textarea name="admission_note" class="form-control"></textarea>
                                </div>
                            </div> --}}
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="progress_note_daily">{{\App\CentralLogics\translate('Progress Note Daily')}}</label>
                                    <textarea name="progress_note_daily" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        {{-- <div class="row pl-2">

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="summary_discharge_note">{{\App\CentralLogics\translate('Summary/Discharge Note')}}</label>
                                    <textarea name="summary_discharge_note" class="form-control"></textarea>
                                </div>
                            </div>


                        </div> --}}


                        <div class="row pl-2">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="blood_pressure">{{\App\CentralLogics\translate('Blood Pressure')}}</label>
                                    <input type="text" name="blood_pressure" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="heart_rate">{{\App\CentralLogics\translate('Heart Rate')}}</label>
                                    <input type="text" name="heart_rate" class="form-control">
                                </div>
                            </div>
                              <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="temperature">{{\App\CentralLogics\translate('Temperature')}}</label>
                                    <input type="text" name="temperature" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label class="input-label" for="respiratory_rate">{{\App\CentralLogics\translate('Respiratory Rate')}}</label>
                                    <input type="text" name="respiratory_rate" class="form-control">
                                </div>
                            </div>
                        </div>

                        <div class="row pl-2">

                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="additional_notes">{{\App\CentralLogics\translate('Additional Notes')}}</label>
                                    <textarea name="additional_notes" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @endif

    @if ($bed->patient_id)
    <div class="modal fade" id="add-admission_note" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_Admission_Note')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="admission_note_form" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{$bed->patient->id}}">
                        <input type="text" hidden name="bed_id" value="{{$bed->id}}">

                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="admission_note">{{\App\CentralLogics\translate('Admission Note')}}</label>
                                    <textarea name="admission_note" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @endif

    @if ($bed->patient_id)
    <div class="modal fade" id="add-discharge_note" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">{{translate('Add_discharge_Note')}}</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <form action="javascript:" method="post" id="discharge_note_form" enctype="multipart/form-data">
                        @csrf
                        <input type="text" hidden name="patient_id" value="{{$bed->patient->id}}">
                        <input type="text" hidden name="bed_id" value="{{$bed->id}}">

                        <div class="row pl-2">
                            <div class="col-12">
                                <div class="form-group">
                                    <label class="input-label" for="summary_discharge_note">{{\App\CentralLogics\translate('Discharge / Summary Note')}}</label>
                                    <textarea name="summary_discharge_note" rows="10" class="form-control"></textarea>
                                </div>
                            </div>
                        </div>


                        <div class="d-flex justify-content-end">
                            <button type="submit" id="" class="btn btn-primary">{{translate('Submit')}}</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
    @endif

    @php($billing=\App\Models\Billing::find(session('last_billing')))

    @if($billing)
        <div class="modal fade" id="print-invoice" tabindex="-1">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">{{\App\CentralLogics\translate('Print Invoice')}}</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body row" style="font-family: emoji;">
                        <div class="col-md-12">
                            <center>
                                <input type="button" class="btn btn-primary non-printable"
                                    onclick="printDiv('printableArea')"
                                    value="{{translate('Proceed, If thermal printer is ready.')}}"/>
                                <a href="{{url()->previous()}}"
                                class="btn btn-danger non-printable">{{\App\CentralLogics\translate('Back')}}</a>
                            </center>
                            <hr class="non-printable">
                        </div>
                        <div class="row" id="printableArea" style="margin: auto;">
                            <div class="col-md-12 d-flex justify-content-center">
                                @include('admin-views.billings.invoices.bedInvoice')
                            </div>
                        </div>


                    </div>
                </div>
            </div>
        </div>

    @endif

@endsection

@push('script')

@endpush


@push('script_2')

<script>

        $(document).ready(function () {
                @if($billing)
                    $('#print-invoice').modal('show');
                    $.get('{{ route('remove_last_billing_session') }}');
                @endif
        });

        function printDiv(divName) {
        var printContents = document.getElementById(divName).innerHTML;
        var originalContents = document.body.innerHTML;
        document.body.innerHTML = printContents;
        window.print();
        document.body.innerHTML = originalContents;
        location.reload();
        }
        function getCartData() {
            var cartData = [];
            $('#serviceCartBody tr').each(function () {
                var serviceId = $(this).find('td[data-service-id]').data('service-id');
                var serviceName = $(this).find('td:first-child').text();
                var quantity = parseInt($(this).find('.quantity-input').val()) || 1;
                var unitCost = parseFloat($(this).find('td:nth-child(3)').text());
                var totalCost = parseFloat($(this).find('td:nth-child(4)').text());

                cartData.push({
                    serviceId: serviceId,
                    serviceName: serviceName,
                    quantity: quantity,
                    unitCost: unitCost,
                    totalCost: totalCost
                });
            });

            return cartData;
        }
       $('#bed_billing_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission

    // Get cart data
    var cartData = getCartData();

    // Get other necessary data
    var subTotal = parseFloat($('#subTotal').text());
    var tax = parseFloat($('#tax').text());
    var grandTotal = parseFloat($('#grandTotal').text());

    // Add cart and other data to form data
    var formData = new FormData(this);
    formData.append('cart', JSON.stringify(cartData));
    formData.append('sub_total', subTotal);
    formData.append('tax', tax);
    formData.append('grand_total', grandTotal);

    // Perform the AJAX request
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route('admin.bed.dissociate_patient') }}',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);



            // Close the modal
            $('#add-prescription').hide();

            // Reset the form
            $('#bed_billing_form')[0].reset();

            // Reload the page
            location.reload();
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
        }
    });
});

$('#note_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route('admin.note.store') }}',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);

            $('#add-prescription').hide();

            // Reset the form
            $('#note_form')[0].reset();

            // Reload the page
            location.reload();
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
        }
    });
});

$('#admission_note_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route('admin.note.store') }}',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);

            $('#add-prescription').hide();

            // Reset the form
            $('#note_form')[0].reset();

            // Reload the page
            location.reload();
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
        }
    });
});
$('#discharge_note_form').on('submit', function (event) {
    event.preventDefault(); // Prevent the default form submission
    var formData = new FormData(this);

    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    $.ajax({
        url: '{{ route('admin.note.store') }}',
        method: 'POST',
        data: formData,
        cache: false,
        contentType: false,
        processData: false,
        success: function (data) {
            console.log(data);

            $('#add-prescription').hide();

            // Reset the form
            $('#note_form')[0].reset();

            // Reload the page
            location.reload();
        },
        error: function (xhr, textStatus, errorThrown) {
            if (xhr.responseJSON && xhr.responseJSON.error) {
                toastr.error(xhr.responseJSON.error, {
                    closeButton: true,
                    progressBar: true
                });
            } else {
                toastr.error('{{ translate("An error occurred while processing your request.") }}', {
                    closeButton: true,
                    progressBar: true
                });
            }
        }
    });
});

</script>
<script>
    $(document).ready(function () {

    var cart = []; // Array to store selected services in the cart

    $('#servicesSelect').change(function () {
        addToCart();
        renderCartTable();
    });

    // Use the change event to capture changes on quantity input fields
    $(document).on('change', '.quantity-input', function () {
        updateCartQuantity($(this).data('cart-id'), $(this).val());
        renderCartTable();
    });
    $(document).on('click', '.remove-btn', function () {
        removeFromCart($(this).data('cart-id'));
        renderCartTable();
    });
    function removeFromCart(cartId) {
    var cartItemIndex = findCartItemIndexById(cartId);
    if (cartItemIndex !== -1) {
        cart.splice(cartItemIndex, 1);
    }
    }
    function addToCart() {
    var selectedservices = $('#servicesSelect option:selected');

    selectedservices.each(function () {
        var serviceId = $(this).val();

        // Check if the service already exists in the cart
        var existingCartItemIndex = findCartItemIndexById2(serviceId);

        if (existingCartItemIndex !== -1) {
            // If the service is already in the cart, increment its quantity
            // cart[existingCartItemIndex].quantity += 1;
        } else {
            // If the service is not in the cart, add it with quantity as the length of stay
            cart.push({
                serviceId: serviceId,
                quantity: {{ $bed->calculateStayDays($bed->id) }},
                unitCost: parseFloat($(this).data('unit-cost')),
                serviceName: $(this).text(),
                cartId: generateCartId()
            });

            // Append a new row to the cart with the quantity input field
            $('#serviceCartBody').append(`
                <tr>
                    <td style="vertical-align:middle" class="col-4">${$(this).text()}</td>
                    <td style="vertical-align:middle" class="col-2"><input type="number" class="form-control quantity-input" value="{{ $bed->calculateStayDays($bed->id) }}" min="1" data-cart-id="${cart[cart.length - 1].cartId}"></td>
                    <td style="vertical-align:middle" class="col-2">${parseFloat($(this).data('unit-cost')).toFixed(2)}</td>
                    <td style="vertical-align:middle" class="col-3">${(parseFloat($(this).data('unit-cost')) * {{ $bed->calculateStayDays($bed->id) }}).toFixed(2)}</td>
                    <td style="vertical-align:middle" class="col-1"><a style="cursor:pointer"  class=" text-danger remove-btn" data-cart-id="${cart[cart.length - 1].cartId}">
                        <i class="tio tio-delete"></i>
                    </a></td>
                </tr>
            `);
        }
    });

    // Update the cart table
    renderCartTable();
}


        function updateCartQuantity(cartId, newQuantity) {
            var cartItemIndex = findCartItemIndexById(cartId);
            if (cartItemIndex !== -1) {
                cart[cartItemIndex].quantity = parseInt(newQuantity) || 1;
            }
        }

        function findCartItemIndexById(cartId) {
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].cartId === cartId) {
                    return i;
                }
            }
            return -1; // Return -1 if not found
        }

        function findCartItemIndexById2(serviceId) {
            for (var i = 0; i < cart.length; i++) {
                if (cart[i].serviceId === serviceId) {
                    return i;
                }
            }
            return -1; // Return -1 if not found
        }

        function renderCartTable() {
            var serviceCartTable = $('#serviceCartTable');
            var serviceCartBody = $('#serviceCartBody');
            serviceCartBody.empty(); // Clear existing rows

            var subTotal = 0;

            cart.forEach(function (cartItem) {
                var totalCost = cartItem.quantity * cartItem.unitCost;

                // Append a new row to the cart with the quantity input field
                serviceCartBody.append(`
                    <tr>
                        <td style="vertical-align:middle" class="col-4" data-service-id="${cartItem.serviceId}">${cartItem.serviceName}</td>
                        <td style="vertical-align:middle" class="col-2"><input type="number" class="form-control quantity-input" value="${cartItem.quantity}" min="1" data-cart-id="${cartItem.cartId}"></td>
                        <td style="vertical-align:middle" class="col-2">${cartItem.unitCost.toFixed(2)}</td>
                        <td style="vertical-align:middle" class="col-3">${totalCost.toFixed(2)}</td>
                        <td style="vertical-align:middle" class="col-1"><a style="cursor:pointer"  class=" text-danger remove-btn" data-cart-id="${cartItem.cartId}">
                            <i class="tio tio-delete"></i>
                            </a></td>

                    </tr>
                `);

                // Accumulate sub-total
                subTotal += totalCost;
            });

            // Display the cart table
            if (cart.length > 0) {
                serviceCartTable.show();
            } else {
                serviceCartTable.hide();
            }

            // Calculate tax (5% of sub-total)
            var taxRate = {{ \App\Models\BusinessSetting::where('key', 'tax')->first()->value ?? 0 }};

            var tax = (taxRate/100) * subTotal;

            // Calculate grand total
            var grandTotal = subTotal + tax;

            // Display values in the tfoot
            $('#subTotal').text(subTotal.toFixed(2));
            $('#tax').text(tax.toFixed(2));
            $('#grandTotal').text(grandTotal.toFixed(2));
        }

        function generateCartId() {
            return Math.random().toString(36).substr(2, 9);
        }
    });


function toggleLabTestTypeField(checkbox) {
    var labTestTypeField = document.getElementById('labTestTypeField');
    labTestTypeField.style.display = checkbox.checked ? 'block' : 'none';
}

function toggleRadiologyTestTypeField(checkbox) {
    var labTestTypeField = document.getElementById('radiologyTestTypeField');
    labTestTypeField.style.display = checkbox.checked ? 'block' : 'none';
}
</script>
    <script>
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#viewer').attr('src', e.target.result);
                }

                reader.readAsDataURL(input.files[0]);
            }
        }

        $("#customFileEg1").change(function () {
            readURL(this);
        });
    </script>

<script>
    $(document).on('ready', function () {
        $('.js-select2-custom').each(function () {
            var select2 = $.HSCore.components.HSSelect2.init($(this));
        });
    });
</script>
@endpush
@push('script')
    <script src="//cdn.ckeditor.com/4.14.1/standard/ckeditor.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('.ckeditor').ckeditor();
        });
    </script>
@endpush
