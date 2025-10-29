@php
    $pdf_header_logo = \App\Models\BusinessSetting::where('key', 'pdf_header_logo')->first()->value;
    $header_logo_path = public_path('storage/app/public/assets/' . $pdf_header_logo);
    $header_logo_path = $pdf_header_logo ? storage_path('app/public/assets/' . $pdf_header_logo) : null;

@endphp
<style>
    @page {
        margin: 0;
    }

    #header {
        position: fixed;
        left: 0;
        top: 0;
        right: 0;
        height: 100px;
        text-align: center;
        background-color: white;
    }

    #header img {
        width: 100%;
        height: 100px;
        display: block;
        margin: 0;
    }
</style>
@if ($header_logo_path && file_exists($header_logo_path))
    <div id="header">
        <img src="{{ 'file://' . $header_logo_path }}">
    </div>
@endif
