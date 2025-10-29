@php
    $pdf_water_mark = \App\Models\BusinessSetting::where('key', 'pdf_water_mark')->first()->value;
    $water_mark_path = public_path('storage/app/public/assets/' . $pdf_water_mark);
    $water_mark_path = $pdf_water_mark ? storage_path('app/public/assets/' . $pdf_water_mark) : null;
@endphp

<style>
    .watermark-top {
        position: fixed;
        top: 50%;
        left: 50%;
        right: 0;
        width: 70%;
        opacity: 0.3;
        transform: translate(-50%, -50%);
        z-index: 0;
    }
</style>
@if ($water_mark_path && file_exists($water_mark_path))
        <img src="{{ 'file://' . $water_mark_path }}" class="watermark-top">
@endif
