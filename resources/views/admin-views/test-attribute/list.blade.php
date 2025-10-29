@extends('layouts.admin.app')

@section('title', translate('Test Attribute List'))

@push('css_or_js')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endpush

@section('content')
    <div class="content container-fluid">
        <div class="d-flex flex-wrap gap-3 align-items-center mb-3">
            <h2 class="text-capitalize mb-0 d-flex align-items-center gap-2">
                <img width="20" src="{{ asset('/assets/admin/img/icons/test_attribute.png') }}" alt="">
                {{ \App\CentralLogics\translate('Test Attribute List') }}
            </h2>
            <span class="badge badge-soft-dark rounded-50 fs-14">{{ $testAttributes->total() }}</span>
        </div>

        <div class="row">
            <div class="col-12">
                <!-- Card -->
                <div class="card">
                    <div class="px-20 py-3">
                        <div class="row gy-2 align-items-center">
                            <div class="col-lg-8 col-sm-12 col-md-12">
                                <form action="{{ url()->current() }}" method="GET" class="row g-2">
                                    <div class="col-lg-4 col-md-4 col-sm-12">
                                        <div class="input-group">
                                            <input id="datatableSearch_" type="search" name="search" class="form-control"
                                                placeholder="{{ translate('Search by test attributeName') }}"
                                                aria-label="Search" value="{{ $search }}" autocomplete="off">
                                        </div>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <select name="parent_test" class="form-control js-select2-custom">
                                            <option value="">{{ translate('All Parent Tests') }}</option>
                                            @foreach ($tests as $test)
                                                <option value="{{ $test->id }}"
                                                    {{ $parent_test == $test->id ? 'selected' : '' }}>
                                                    {{ $test->test_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-lg-4 col-md-4 col-sm-6">
                                        <div class="d-flex gap-1">
                                            <button type="submit" class="btn btn-primary flex-fill">
                                                {{ \App\CentralLogics\translate('Filter') }}
                                            </button>
                                            <a href="{{ url()->current() }}" class="btn btn-outline-secondary">
                                                {{ \App\CentralLogics\translate('Clear') }}
                                            </a>
                                        </div>
                                    </div>
                                </form>
                            </div>
                            @if (auth('admin')->user()->can('test_attribute.add-new'))
                                <div
                                    class="col-lg-4 col-sm-12 col-md-12 d-flex justify-content-lg-end justify-content-sm-start">
                                    <a href="{{ route('admin.test_attribute.add-new') }}" class="btn btn-primary">
                                        <i class="tio-add"></i>
                                        {{ \App\CentralLogics\translate('Add New Test Attribute') }}
                                    </a>
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive datatable-custom">
                        <table
                            class="table table-borderless table-thead-bordered table-nowrap table-align-middle card-table">
                            <thead class="thead-light">
                                <tr>
                                    <th>{{ \App\CentralLogics\translate('SL') }}</th>
                                    <th>{{ \App\CentralLogics\translate('attribute_name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Test Name') }}</th>
                                    <th>{{ \App\CentralLogics\translate('attribute_type') }}</th>
                                    <th>{{ \App\CentralLogics\translate('Index') }}</th>
                                    <th class="text-center">{{ \App\CentralLogics\translate('action') }}</th>
                                </tr>
                            </thead>
                            <tbody id="set-rows">
                                @foreach ($testAttributes as $key => $testAttribute)
                                    <tr>
                                        <td>{{ $testAttributes->firstitem() + $key }}</td>
                                        <td>{{ $testAttribute->attribute_name }}</td>
                                        <td>{{ $testAttribute->test->test_name }}</td>
                                        <td>{{ $testAttribute->attribute_type == 'Qualitative' ? 'Qualitative' : 'Quantitative' }}
                                        </td>
                                        <td>{{ $testAttribute->index }}</td>
                                        <td>
                                            <div class="d-flex gap-2 justify-content-center">
                                                @if (auth('admin')->user()->can('test_attribute.view'))
                                                    <button class="btn btn-outline-primary square-btn"
                                                        onclick="viewAttributedetail(`{{ addslashes($testAttribute) }}`)">
                                                        <i class="tio tio-visible"></i>
                                                    </button>
                                                @endif
                                                @if (auth('admin')->user()->can('test_attribute.edit'))
                                                    <a class="btn btn-outline-primary square-btn"
                                                        href="{{ route('admin.test_attribute.edit', [$testAttribute->id]) }}">
                                                        <i class="tio tio-edit"></i>
                                                    </a>
                                                @endif
                                                @if (auth('admin')->user()->can('test_attribute.delete'))
                                                    <a class="btn btn-outline-danger square-btn" href="javascript:"
                                                        onclick="form_alert('test_attribute-{{ $testAttribute->id }}','{{ \App\CentralLogics\translate('Want to delete this test attribute?') }}')">
                                                        <i class="tio tio-delete"></i>
                                                    </a>
                                                @endif
                                            </div>
                                            <form action="{{ route('admin.test_attribute.delete', [$testAttribute->id]) }}"
                                                method="post" id="test_attribute-{{ $testAttribute->id }}">
                                                @csrf @method('delete')
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach

                            </tbody>
                        </table>
                    </div>
                    <!-- End Table -->

                    <!-- Pagination -->
                    <div class="table-responsive mt-4 px-3">
                        <div class="d-flex justify-content-end">
                            {!! $testAttributes->links() !!}
                        </div>
                    </div>
                    @if (count($testAttributes) == 0)
                        <div class="text-center p-4">
                            <img class="mb-3" src="{{ asset('/assets/admin') }}/svg/illustrations/sorry.svg"
                                alt="Image Description" style="width: 7rem;">
                            <p class="mb-0">{{ translate('No data to show') }}</p>
                        </div>
                    @endif
                </div>
                <!-- End Card -->
            </div>
        </div>

        <!-- Modal for Viewing Attribute Details -->
        <div class="modal fade" id="viewTestAttributeModal" tabindex="-1" aria-labelledby="viewTestAttributeModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="viewTestAttributeModalLabel">Test Attribute Details</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <!-- Attribute Details -->
                        <h4 id="attributeName"></h4>
                        <p><strong>Type:</strong> <span id="attributeType"></span></p>
                        <p><strong>Test Name:</strong> <span id="testName"></span></p>
                        <p><strong>Test Category:</strong> <span id="testCategory"></span></p>
                        <p><strong>Unit:</strong> <span id="unit"></span></p>
                        <hr>

                        <!-- Attribute Options -->
                        <h5>Options</h5>
                        <ul id="attributeOptions"></ul>

                        <!-- Attribute References -->
                        <h5>References</h5>
                        <div id="attributeReferences"></div>
                    </div>
                </div>
            </div>
        </div>

    </div>

@endsection

@push('script_2')
    <script>
        function viewAttributedetail(attribute) {
            const attributeData = JSON.parse(attribute);

            // Set the basic info
            $('#attributeName').text(attributeData.attribute_name);
            $('#attributeType').text(attributeData.attribute_type);
            $('#attributeCategory').text(attributeData.test_category);
            $('#testName').text(attributeData.test.test_name);
            $('#testCategory').text(attributeData.test.test_category.name);

            $('#unit').text(attributeData.unit ? attributeData.unit.name : 'No unit');

            // Populate options
            let optionsHtml = '';
            if (attributeData.options && attributeData.options.length > 0) {
                attributeData.options.forEach(function(option) {
                    optionsHtml += '<li>' + option.option_value + '</li>';
                });
            } else {
                optionsHtml = '<li>No options available</li>';
            }
            $('#attributeOptions').html(optionsHtml);

            // Convert operator symbols
            function convertOperator(op) {
                switch (op) {
                    case '>=':
                        return ' ≥ ';
                    case '<=':
                        return ' ≤ ';
                    case '>':
                        return ' > ';
                    case '<':
                        return ' < ';
                    case '=':
                        return ' = ';
                    default:
                        return '';
                }
            }

            function formatRange(lower, upper, lowerOp, upperOp) {
                if (lower !== null && upper !== null) {
                    return `${lower} – ${upper}`;
                } else if (lower !== null) {
                    return `${convertOperator(lowerOp)}${lower}`;
                } else if (upper !== null) {
                    return `${convertOperator(upperOp)}${upper}`;
                } else {
                    return '';
                }
            }

            function formatReferenceText(ref) {
                if (ref.reference_text) {
                    return ref.reference_text;
                }

                let parts = [];

                if (ref.min_age !== null && ref.max_age !== null) {
                    parts.push(`Age ${ref.min_age} – ${ref.max_age}`);
                }

                const range = formatRange(ref.lower_limit, ref.upper_limit, ref.lower_operator, ref.upper_operator);
                if (range) parts.push(range);

                return parts.join(', ');
            }


            function buildReferenceDisplay(references) {
                if (references.length === 2 && references.every(r => r.gender)) {
                    let maleRef = references.find(r => r.gender === 'male');
                    let femaleRef = references.find(r => r.gender === 'female');

                    if (maleRef && femaleRef) {
                        let maleText = `Male ${formatReferenceText(maleRef)}`;
                        let femaleText = `Female ${formatReferenceText(femaleRef)}`;
                        return `${maleText}&emsp;&emsp;${femaleText}`;
                    }
                }

                return references.map(ref => {
                    let gender = ref.gender ? ref.gender.charAt(0).toUpperCase() + ref.gender.slice(1) + ' ' : '';
                    return `${gender}${formatReferenceText(ref)}`;
                }).join('<br>');
            }

            // Populate references
            let referencesHtml = '';
            if (attributeData.attribute_references && attributeData.attribute_references.length > 0) {
                referencesHtml = buildReferenceDisplay(attributeData.attribute_references);
            } else {
                referencesHtml = '<p>No reference range</p>';
            }
            $('#attributeReferences').html(referencesHtml);

            // Show the modal
            $('#viewTestAttributeModal').modal('show');
        }
    </script>
@endpush
