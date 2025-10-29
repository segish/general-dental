@php
    $pdf_footer_logo = \App\Models\BusinessSetting::where('key', 'pdf_footer_logo')->first()?->value;
    $footer_logo_path = $pdf_footer_logo ? storage_path('app/public/assets/' . $pdf_footer_logo) : null;
@endphp

<style>
    @page {
        margin: 0;
    }

    #footer {
        position: fixed;
        left: 0;
        bottom: 0;
        right: 0;
        height: 60px;
        text-align: center;
        background-color: white;
    }

    #footer img {
        width: 100%;
        height: 60px;
        display: block;
        margin: 0;
    }
</style>

@if ($pdf_footer_logo && file_exists($footer_logo_path))
    <div id="footer">
        <img src="{{ 'file://' . $footer_logo_path }}">
    </div>
@endif
