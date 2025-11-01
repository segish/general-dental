@if (auth('admin')->user()->can('dental_chart.list') && $visit->dentalCharts && $visit->dentalCharts->count() > 0)
    <fieldset class="border border-primary mt-3 p-3 rounded">
        <legend class="float-none w-auto px-3 py-1 bg-light border border-primary rounded-sm"
            style="font-weight: bold; font-size: 18px; color:white; background: linear-gradient(90deg, #0dcaf0 0%, #0d6efd 100%)">
            <div class="pr-1">
                <i class="tio-chart-line-up mr-1"></i>Dental Charts
            </div>
        </legend>

        <div class="p-3">
            @foreach ($visit->dentalCharts as $chart)
                <div class="card mb-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="mb-0">
                                <strong>{{ $chart->title ?: ucfirst(str_replace('_', ' ', $chart->chart_type)) }}</strong>
                                <span
                                    class="badge badge-info ml-2">{{ ucfirst(str_replace('_', ' ', $chart->chart_type)) }}</span>
                            </h6>
                            <small class="text-muted">
                                Created by: {{ $chart->creator->f_name ?? 'N/A' }} {{ $chart->creator->l_name ?? '' }}
                                on {{ $chart->created_at->format('M d, Y h:i A') }}
                            </small>
                        </div>
                        <div>
                            @if (auth('admin')->user()->can('dental_chart.list'))
                                <button type="button" class="btn btn-sm btn-info"
                                    onclick="viewDentalChart({{ $chart->id }})" title="View Full Chart">
                                    <i class="tio-visible"></i> {{ translate('View') }}
                                </button>
                            @endif
                            @if (auth('admin')->user()->can('dental_chart.edit'))
                                <button type="button" class="btn btn-sm btn-primary"
                                    onclick="editDentalChart({{ $chart->id }})">
                                    <i class="tio-edit"></i> {{ translate('Edit') }}
                                </button>
                            @endif
                            @if (auth('admin')->user()->can('dental_chart.delete'))
                                <button type="button" class="btn btn-sm btn-danger"
                                    onclick="deleteDentalChart({{ $chart->id }})">
                                    <i class="tio-delete"></i> {{ translate('Delete') }}
                                </button>
                            @endif
                        </div>
                    </div>
                    <div class="card-body">
                        @if ($chart->notes)
                            <div class="mb-3">
                                <strong>{{ translate('Notes') }}:</strong>
                                <p class="mb-0 text-muted">{{ $chart->notes }}</p>
                            </div>
                        @endif

                        <!-- Chart Display -->
                        <div class="chart-display-container">
                            @if ($chart->chart_type === 'image_annotation' && $chart->image_path)
                                <div class="position-relative mb-2">
                                    <img src="{{ asset('storage/' . $chart->image_path) }}" alt="Chart Background"
                                        class="img-fluid" style="max-width: 800px;">
                                    <canvas id="chart-canvas-{{ $chart->id }}" width="800" height="600"
                                        style="display: none;"></canvas>
                                </div>
                            @else
                                <div class="border rounded p-2" style="background: #f8f9fa; overflow-x: auto;">
                                    @if ($chart->chart_type === 'periodontal')
                                        <canvas id="chart-canvas-{{ $chart->id }}" width="1024" height="1280"
                                            style="border: 1px solid #ddd; display: block;"></canvas>
                                    @else
                                        <canvas id="chart-canvas-{{ $chart->id }}" width="800" height="600"
                                            style="border: 1px solid #ddd; display: block;"></canvas>
                                    @endif
                                </div>
                            @endif
                        </div>
                        @push('script_2')
                            <script>
                                $(document).ready(function() {
                                    const canvasId = 'chart-canvas-{{ $chart->id }}';
                                    const chartData = @json($chart->chart_data);

                                    if (typeof fabric !== 'undefined' && chartData) {
                                        // Determine canvas dimensions based on chart type
                                        @if ($chart->chart_type === 'periodontal')
                                            const canvasWidth = 1024;
                                            const canvasHeight = 1280;
                                        @else
                                            const canvasWidth = 800;
                                            const canvasHeight = 600;
                                        @endif

                                        const canvas = new fabric.Canvas(canvasId, {
                                            width: canvasWidth,
                                            height: canvasHeight
                                        });

                                        @if ($chart->chart_type === 'image_annotation' && $chart->image_path)
                                            // Load background image first
                                            fabric.Image.fromURL('{{ asset('storage/' . $chart->image_path) }}', function(img) {
                                                img.scaleToWidth(canvas.width);
                                                img.scaleToHeight(canvas.height);
                                                canvas.setBackgroundImage(img, function() {
                                                    // Then load chart data
                                                    if (chartData) {
                                                        canvas.loadFromJSON(chartData, function() {
                                                            canvas.renderAll();
                                                        });
                                                    }
                                                });
                                            });
                                        @else
                                            // Load chart data directly
                                            if (chartData) {
                                                canvas.loadFromJSON(chartData, function() {
                                                    canvas.renderAll();
                                                });
                                            }
                                        @endif
                                    }
                                });
                            </script>
                        @endpush

                        <!-- Tooth Data Summary (if available) -->
                        @if ($chart->tooth_data && is_array($chart->tooth_data) && count($chart->tooth_data) > 0)
                            <div class="mt-3">
                                <strong>{{ translate('Tooth Data Summary') }}:</strong>
                                <div class="table-responsive mt-2">
                                    <table class="table table-sm table-bordered">
                                        <thead>
                                            <tr>
                                                <th>Tooth Number</th>
                                                <th>Status</th>
                                                <th>Conditions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach ($chart->tooth_data as $toothNumber => $toothInfo)
                                                <tr>
                                                    <td>{{ $toothNumber }}</td>
                                                    <td>
                                                        <span
                                                            class="badge badge-{{ $toothInfo['status'] === 'present' ? 'success' : 'danger' }}">
                                                            {{ ucfirst($toothInfo['status'] ?? 'N/A') }}
                                                        </span>
                                                    </td>
                                                    <td>
                                                        @if (isset($toothInfo['conditions']) && is_array($toothInfo['conditions']))
                                                            @foreach ($toothInfo['conditions'] as $condition)
                                                                <span
                                                                    class="badge badge-secondary">{{ ucfirst($condition) }}</span>
                                                            @endforeach
                                                        @else
                                                            <span class="text-muted">None</span>
                                                        @endif
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    </fieldset>
@endif
