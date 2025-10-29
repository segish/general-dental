<h2 style="text-align: center;">ULTRASOUND REPORT</h2>

<div class="section">
    <div>Name: <span class="underline">{{ $patient->full_name ?? '__________' }}</span></div>
    <div>Sex: <span class="underline">{{ $patient->gender ?? '__________' }}</span></div>
    <div>Age: <span class="underline">{{ $patient->age ?? '__________' }}</span></div>
</div>

<div class="section">
    <div class="bold">Examination Name:</div>
    <div>Ultrasound, abdomen & pelvis</div>
</div>

<div class="section">
    <div class="bold">Findings:-</div>
    @php
        $findings = $radiologyResult->attributes->where('attribute_name', 'findings')->first();
    @endphp
    <p>{{ $findings->value ?? str_repeat('_________________________________________________________________', 5) }}</p>
</div>

<div class="section">
    <div class="bold">Conclusion:</div>
    @php
        $conclusion = $radiologyResult->attributes->where('attribute_name', 'conclusion')->first();
    @endphp
    <p>{{ $conclusion->value ?? str_repeat('_________________________________________________________________', 2) }}
    </p>
</div>

<div class="section">
    <div>Reported by: <span class="underline">{{ $conclusion->reported_by ?? '__________' }}</span></div>
    <div>Signature: <span class="underline">__________</span></div>
</div>
