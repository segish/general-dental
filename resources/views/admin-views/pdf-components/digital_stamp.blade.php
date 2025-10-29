@php
    $digital_stamp = \App\Models\BusinessSetting::where('key', 'digital_stamp')->first()->value;
    $digital_stamp_path = public_path('storage/app/public/assets/' . $digital_stamp);
    $digital_stamp_path = $digital_stamp ? storage_path('app/public/assets/' . $digital_stamp) : null;
@endphp

<style>
    .digital_stamp {
        position: fixed;
        bottom: 60px;
        left: 80%;
        right: 0;
        width: 25%;
        opacity: 0.5;
        transform: translate(-50%, -50%);
        z-index: 999;
    }
</style>
@if ($digital_stamp_path && file_exists($digital_stamp_path))
        <img src="{{ 'file://' . $digital_stamp_path }}" class="digital_stamp">
@endif
