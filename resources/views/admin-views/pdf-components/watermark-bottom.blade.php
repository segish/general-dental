@php
    $wm_bottom = public_path('assets/admin/img/wm-bottom.png');
@endphp

<style>
    .watermark-bottom {
        position: fixed;
        top: 80%;
        left: 25%;
        width: 50%;
        opacity: 0.2;
        transform: translate(-50%, -50%);
        z-index: 0;
    }
</style>

<img src="file://{{ $wm_bottom }}" class="watermark-bottom">
