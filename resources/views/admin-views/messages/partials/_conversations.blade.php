<div class="">
    <!-- Header -->
    <div class="border px-3 py-2 rounded mb-3">
        <div class="media gap-3">
            <div class="avatar rounded-circle">
                <img class="img-fit rounded-circle" src="{{ asset('/storage/app/public/profile/' . $user['image']) }}"
                    onerror="this.src='{{ asset(config('app.asset_path') . '/admin') }}/img/160x160/img1.jpg'"
                    alt="Image Description">
            </div>
            <h5 class="mb-0">
                <div>{{ $user['f_name'] . ' ' . $user['l_name'] }}</div>
                <div class="fs-12 font-weight-normal">{{ $user['phone'] }}</div>
            </h5>
        </div>
    </div>

    <div class="chat_conversation">
        <div class="row">
            @foreach ($convs as $key => $con)
                @if (($con->message != null && $con->reply == null) || $con->is_reply == false)
                    <div class="col-12">
                        <div class="received_msg">
                            @if (isset($con->message))
                                <div class="msg">{{ $con->message }}</div>
                                <span class="time_date">{{ date('Y-m-d h:i A', strtotime($con->created_at)) }}</span>
                            @endif
                            <?php try {?>
                            @if ($con->attachment != null && $con->attachment != 'null' && count(json_decode($con->attachment, true)) > 0)
                                @php($image_array = json_decode($con->attachment, true))
                                @foreach ($image_array as $image)
                                    <img src="{{ $image }}"
                                        onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/900x400/img1.jpg') }}'">
                                    <br />
                                @endforeach
                            @endif
                            <?php }catch (\Exception $e) {} ?>

                            @if (isset($con->image))
                                <img src="{{ $con->image }}"
                                    onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/900x400/img1.jpg') }}'">
                                <br />
                            @endif
                        </div>
                    </div>
                @endif
                @if (($con->reply != null && $con->message == null) || $con->is_reply == true)
                    <div class="col-12">
                        <div class="outgoing_msg">
                            @if (isset($con->reply))
                                <div class="msg">{{ $con->reply }}</div>
                                <span class="time_date">{{ date('Y-m-d h:i A', strtotime($con->created_at)) }}</span>
                            @endif
                            <?php try {?>
                            <div class="row">
                                @if ($con->attachment != null && $con->attachment != 'null' && count(json_decode($con->attachment, true)) > 0)
                                    @php($image_array = json_decode($con->attachment, true))
                                    @foreach ($image_array as $key => $image)
                                        @php($image_url = $image)
                                        <div class="col-12 @if (count(json_decode($con->attachment, true)) > 1) col-md-6 @endif">
                                            <img src="{{ asset('/storage/app/public/conversation') . '/' . $image_url }}"
                                                onerror="this.src='{{ asset(config('app.asset_path') . '/admin/img/900x400/img1.jpg') }}'"><br />
                                        </div>
                                    @endforeach
                                @endif
                            </div>
                            <?php }catch (\Exception $e) {} ?>
                        </div>
                    </div>
                @endif
            @endforeach
            <div id="scroll-here"></div>
        </div>
    </div>
</div>

<form action="javascript:" method="post" id="reply-form">
    @csrf
    <div class="card mb-2">
        <div class="p-2">
            <!-- Quill -->
            <div class="quill-custom_">
                <textarea class="border-0 w-100" name="reply" placeholder="{{ \App\CentralLogics\translate('Type Here...') }}"></textarea>
            </div>
            <!-- End Quill -->

            <div id="accordion" class="d-flex gap-2 justify-content-end">
                <button class="btn btn-primary btn-sm collapsed" data-toggle="collapse" data-target="#collapseTwo"
                    aria-expanded="false" aria-controls="collapseTwo">
                    {{ \App\CentralLogics\translate('Upload') }}
                    <i class="tio-upload"></i>
                </button>
                <button type="submit" onclick="replyConvs('{{ route('admin.message.store', [$user->id]) }}')"
                    class="btn btn-primary btn-sm">{{ \App\CentralLogics\translate('send') }} <i class="tio-send"></i>
                </button>
            </div>

            <div id="collapseTwo" class="collapse" aria-labelledby="headingTwo" data-parent="#accordion">
                <div class="row mt-3" id="coba"></div>
            </div>
        </div>
    </div>
</form>

<script>
    $(document).ready(function() {
        $('.scroll-down').animate({
            scrollTop: $('#scroll-here').offset().top
        }, 0);
    });
</script>

{{-- Multi Image Picker --}}
<script>
    $('#collapseTwo').on('show.bs.collapse', function() {
        spartanMultiImagePicker();
    })

    $('#collapseTwo').on('hidden.bs.collapse', function() {
        document.querySelector("#coba").innerHTML = "";
    })
</script>
<script src="{{ asset(config('app.asset_path') . '/admin') }}/js/tags-input.min.js"></script>
<script src="{{ asset(config('app.asset_path') . '/admin/js/spartan-multi-image-picker.js') }}"></script>
<script>
    function spartanMultiImagePicker() {
        document.querySelector("#coba").innerHTML = "";

        $("#coba").spartanMultiImagePicker({
            fieldName: 'images[]',
            maxCount: 4,
            rowHeight: '10%',
            groupClassName: 'col-3',
            maxFileSize: '',
            {{-- placeholderImage: { --}}
            {{--    image: '{{asset(config('app.asset_path') . '/back-end/img/400x400/img2.jpg')}}', --}}
            {{--    width: '100%', --}}
            {{-- }, --}}
            dropFileLabel: "Drop Here",
            onAddRow: function(index, file) {

            },
            onRenderedPreview: function(index) {

            },
            onRemoveRow: function(index) {

            },
            onExtensionErr: function(index, file) {
                toastr.error(
                    '{{ \App\CentralLogics\translate('Please only input png or jpg type file') }}', {
                        CloseButton: true,
                        ProgressBar: true
                    });
            },
            onSizeErr: function(index, file) {
                toastr.error('{{ \App\CentralLogics\translate('File size too big') }}', {
                    CloseButton: true,
                    ProgressBar: true
                });
            }
        });
    }
</script>
