<?php

namespace App\Http\Controllers\admin;

use App\Models\Test;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\TestAttribute;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use App\CentralLogics\Helpers;
use App\Models\Unit;
use Illuminate\Contracts\View\View;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class AttributeController extends Controller
{
    //
    function __construct(
        private TestAttribute $testAttribute
    ) {
        $this->middleware('checkAdminPermission:test_attribute.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:test_attribute.add-new,index')->only(['index']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $roles = Role::where('guard_name', 'admin')->get();
        $tests = Test::all();
        $units = Unit::all();
        return view('admin-views.test-attribute.index', compact('roles', 'tests', 'units'));
    }

    // public function fetchTestAttributes(Request $request)
    // {
    //     $test = Test::findOrFail($request->testId);
    //     $attributes = $test->attributes()->with('options')->get();
    //     return response()->json($attributes, 200);
    // }
    public function fetchTestAttributes(Request $request)
    {
        $test = Test::findOrFail($request->testId);

        // Get attributes with options, ordered by `index` (nulls last)
        $attributes = $test->attributes()
            ->with('options')
            ->orderByRaw('CASE WHEN `index` IS NULL THEN 1 ELSE 0 END, `index` ASC')
            ->get();

        return response()->json($attributes, 200);
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        $parent_test = $request['parent_test'];

        // Start the query with eager loading for unit, attributeReferences, and options
        $query = $this->testAttribute->with(['unit', 'attributeReferences', 'options', 'test', 'test.testCategory']);

        // Apply search filter
        if ($request->has('search') && !empty($search)) {
            $key = explode(' ', $search);
            $query = $query->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('id', 'like', "%{$value}%")
                        ->orWhere('attribute_name', 'like', "%{$value}%")
                        ->orWhere('test_category', 'like', "%{$value}%")
                        ->orWhere('attribute_type', 'like', "%{$value}%");

                    $q->orWhereHas('test', function ($testQuery) use ($value) {
                        $testQuery->where('test_name', 'like', "%{$value}%");
                    });
                }
            });
        }

        // Apply parent test filter
        if ($request->has('parent_test') && !empty($parent_test)) {
            $query->where('test_id', $parent_test);
        }

        // Apply ordering and pagination
        $testAttributes = $query->latest()->paginate(Helpers::pagination_limit())->appends($query_param);
        $tests = Test::all(); // Get all tests for the dropdown

        $query_param = [
            'search' => $search,
            'parent_test' => $parent_test
        ];

        return view('admin-views.test-attribute.list', compact('testAttributes', 'search', 'parent_test', 'tests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permission = Permission::get();
        return view('admin-views.test-attribute.create', compact('permission'));
    }
    /**
     * Store a newly created resource in storage.
     */
    // public function store(Request $request)
    // {
    //     $rules = [
    //         'test_id' => 'required|exists:tests,id',
    //         'attribute_name' => 'required|string|max:255',
    //         'attribute_type' => 'required|in:Qualitative,Quantitative',
    //         'has_options' => 'boolean',
    //         'default_required' => 'boolean',
    //     ];

    //     // Add validation rules based on has_options
    //     if ($request->has_options) {
    //         $rules['options'] = 'required|array';
    //         $rules['options.*'] = 'required|string|max:255';
    //     } else {
    //         $rules['lower_limit'] = 'nullable|numeric';
    //         $rules['upper_limit'] = 'nullable|numeric';
    //         $rules['unit'] = 'nullable|string|max:50';
    //         $rules['lower_operator'] = 'nullable|in:<,<=,=';
    //         $rules['upper_operator'] = 'nullable|in:<,<=,=';
    //         $rules['reference_text'] = 'nullable|string|max:255';
    //     }

    //     $request->validate($rules);

    //     try {
    //         $attribute = new TestAttribute([
    //             'test_id' => $request->test_id,
    //             'attribute_name' => $request->attribute_name,
    //             'attribute_type' => $request->attribute_type,
    //             'has_options' => $request->has_options,
    //             'lower_limit' => $request->lower_limit,
    //             'upper_limit' => $request->upper_limit,
    //             'unit' => $request->unit,
    //             'lower_operator' => $request->lower_operator,
    //             'upper_operator' => $request->upper_operator,
    //             'reference_text' => $request->reference_text,
    //             'default_required' => $request->default_required,
    //         ]);
    //         $attribute->save();

    //         // Store options if has_options is true
    //         if ($request->has_options && $request->has('options')) {
    //             foreach ($request->options as $optionValue) {
    //                 $attribute->options()->create([
    //                     'option_value' => $optionValue
    //                 ]);
    //             }
    //         }

    //         Toastr::success(translate('Test Attribute saved successfully!'));
    //         return redirect()->route('admin.test_attribute.list');
    //     } catch (\Exception $e) {
    //         Toastr::error(translate($e->getMessage()));
    //         return redirect()->back()->withInput(); //
    //     }
    // }


    public function store(Request $request)
    {
        // dd($request);
        $request->validate([
            'test_id' => 'required|exists:tests,id',
            'attribute_name' => 'required|string|max:255',
            'test_category' => 'required|in:Macroscopic,Microscopic,Chemical,Text,Result',
            'attribute_type' => 'required|in:Qualitative,Quantitative',
            'unit_id' => 'nullable|exists:units,id',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'references' => 'nullable|array',
            'references.*.min_age' => 'nullable|numeric',
            'references.*.max_age' => 'nullable|numeric',
            'references.*.gender' => 'nullable|in:male,female,both',
            'references.*.is_pregnant' => 'nullable|boolean',
            'references.*.min_value' => 'nullable|numeric',
            'references.*.max_value' => 'nullable|numeric',
            'index' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('test_attributes')
                    ->where(function ($query) use ($request) {
                        return $query->where('test_id', $request->test_id)
                            ->whereNotNull('index');
                    }),
            ],
        ], [
            'index.unique' => 'The index number is already used for this test.',
            'index.integer' => 'Index must be a whole number.',
            'index.min' => 'Index must be at least 1.',
        ]);

        DB::beginTransaction();

        try {
            $testAttribute = TestAttribute::create([
                'test_id' => $request->test_id,
                'attribute_name' => $request->attribute_name,
                'attribute_type' => $request->attribute_type,
                'test_category' => $request->test_category,
                'has_options' => $request->has_options,
                'unit_id' => $request->unit_id,
                'default_required' => $request->default_required,
                'index' => $request->index ?? null,
            ]);

            // 2. Save Attribute Options (for dropdown/select)
            if ($request->has('options') && is_array($request->options)) {
                foreach ($request->options as $option) {
                    $testAttribute->options()->create([
                        'option_value' => $option,
                    ]);
                }
            }

            // 3. Save Attribute References (for age/gender/value ranges)
            if ($request->has('references') && is_array($request->references)) {
                foreach ($request->input('references') as $ref) {
                    $testAttribute->attributeReferences()->create([
                        'min_age' => $ref['min_age'] ?? null,
                        'max_age' => $ref['max_age'] ?? null,
                        'gender' => $ref['gender'] ?? null,
                        'is_pregnant' => $ref['is_pregnant'] ?? null,
                        'min_value' => $ref['min_value'] ?? null,
                        'max_value' => $ref['max_value'] ?? null,
                        'lower_operator' => $ref['lower_operator'] ?? null,
                        'lower_limit' => $ref['lower_limit'] ?? null,
                        'upper_operator' => $ref['upper_operator'] ?? null,
                        'upper_limit' => $ref['upper_limit'] ?? null,
                        'reference_text' => $ref['reference_text'] ?? null,
                        'notes' => $ref['notes'] ?? null,
                    ]);
                }
            }

            DB::commit();
            Toastr::success(translate('Test Attribute saved successfully!'));
            return redirect()->route('admin.test_attribute.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }


    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $role = Role::find($id);
        $rolePermissions = Permission::join("role_has_permissions", "role_has_permissions.permission_id", "=", "permissions.id")
            ->where("role_has_permissions.role_id", $id)
            ->get();

        return view('admin-views.test-attribute.show', compact('role', 'rolePermissions'));
    }

    public function edit($id)
    {
        $tests = Test::all();
        $testAttribute = TestAttribute::with(['attributeReferences', 'options'])->find($id);
        $units = Unit::all();
        // dd($testAttribute);
        return view('admin-views.test-attribute.edit', compact('testAttribute', 'tests', 'units'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $testAttribute = TestAttribute::findOrFail($id);

        // Validate the request data
        $validatedData = $request->validate([
            'test_id' => 'required|exists:tests,id',
            'attribute_name' => 'required|string|max:100',
            'attribute_type' => 'required|in:Qualitative,Quantitative',
            'test_category' => 'required|in:Macroscopic,Microscopic,Chemical,Text,Result',
            'has_options' => 'required|boolean',
            'unit_id' => 'nullable|exists:units,id',
            'default_required' => 'required|boolean',
            'options' => 'nullable|array',
            'options.*' => 'string|max:255',
            'references' => 'nullable|array',
            'references.*.gender' => 'nullable|in:male,female',
            'references.*.min_age' => 'nullable|integer|min:0',
            'references.*.max_age' => 'nullable|integer|min:0|gte:references.*.min_age',
            'references.*.is_pregnant' => 'nullable|boolean',
            'references.*.lower_operator' => 'nullable|in:>,>=,=',
            'references.*.lower_limit' => 'nullable|numeric',
            'references.*.upper_operator' => 'nullable|in:<,<=,=',
            'references.*.upper_limit' => 'nullable|numeric',
            'references.*.reference_text' => 'nullable|string|max:255',
            'references.*.id' => 'nullable|exists:test_attribute_references,id',
            'index' => [
                'nullable',
                'integer',
                'min:1',
                Rule::unique('test_attributes')
                    ->where(function ($query) use ($request) {
                        return $query->where('test_id', $request->test_id)
                            ->whereNotNull('index');
                    })
                    ->ignore($id), // ignore this record
            ],
        ], [
            'index.unique' => 'The index number is already used for this test.',
            'index.integer' => 'Index must be a whole number.',
            'index.min' => 'Index must be at least 1.',
        ]);

        // Find the test attribute to update
        $testAttribute = TestAttribute::findOrFail($id);

        // Start database transaction
        DB::beginTransaction();

        try {
            // Update the main test attribute
            $testAttribute->update([
                'test_id' => $validatedData['test_id'],
                'attribute_name' => $validatedData['attribute_name'],
                'attribute_type' => $validatedData['attribute_type'],
                'test_category' => $validatedData['test_category'],
                'has_options' => $validatedData['has_options'],
                'unit_id' => $validatedData['unit_id'] ?? null,
                'default_required' => $validatedData['default_required'],
                'index' => $validatedData['index'] ?? null,
            ]);

            // Handle options if has_options is true
            if ($validatedData['has_options']) {
                // Delete existing options if any
                if (method_exists($testAttribute, 'options')) {
                    $testAttribute->options()->delete();
                }

                // Add new options
                if (!empty($validatedData['options'])) {
                    foreach ($validatedData['options'] as $option) {
                        $testAttribute->options()->create([
                            'option_value' => $option
                        ]);
                    }
                }
            }

            // Handle references
            $submittedReferenceIds = [];

            if (!empty($validatedData['references'])) {
                foreach ($validatedData['references'] as $referenceData) {
                    if (!empty($referenceData['id'])) {
                        // Update existing reference
                        $reference = $testAttribute->attributeReferences()
                            ->where('id', $referenceData['id'])
                            ->first();

                        if ($reference) {
                            $reference->update([
                                'gender' => $referenceData['gender'] ?? null,
                                'min_age' => $referenceData['min_age'] ?? null,
                                'max_age' => $referenceData['max_age'] ?? null,
                                'is_pregnant' => $referenceData['is_pregnant'] ?? null,
                                'lower_operator' => $referenceData['lower_operator'] ?? null,
                                'lower_limit' => $referenceData['lower_limit'] ?? null,
                                'upper_operator' => $referenceData['upper_operator'] ?? null,
                                'upper_limit' => $referenceData['upper_limit'] ?? null,
                                'reference_text' => $referenceData['reference_text'] ?? null,
                            ]);
                            $submittedReferenceIds[] = $referenceData['id'];
                        }
                    } else {
                        // Create new reference
                        $newReference = $testAttribute->attributeReferences()->create([
                            'gender' => $referenceData['gender'] ?? null,
                            'min_age' => $referenceData['min_age'] ?? null,
                            'max_age' => $referenceData['max_age'] ?? null,
                            'is_pregnant' => $referenceData['is_pregnant'] ?? null,
                            'lower_operator' => $referenceData['lower_operator'] ?? null,
                            'lower_limit' => $referenceData['lower_limit'] ?? null,
                            'upper_operator' => $referenceData['upper_operator'] ?? null,
                            'upper_limit' => $referenceData['upper_limit'] ?? null,
                            'reference_text' => $referenceData['reference_text'] ?? null,
                        ]);
                        $submittedReferenceIds[] = $newReference->id;
                    }
                }
            }

            // Delete references that weren't submitted (were removed from the form)
            $testAttribute->attributeReferences()
                ->whereNotIn('id', $submittedReferenceIds)
                ->delete();

            // Commit the transaction
            DB::commit();


            // if (!empty($validatedData['references'])) {
            //     $existingReferenceIds = collect($validatedData['references'])
            //         ->pluck('id')
            //         ->filter()
            //         ->toArray();

            //     // Delete references not present in the request
            //     if (method_exists($testAttribute, 'references')) {
            //         $testAttribute->attributeReferences()
            //             ->whereNotIn('id', $existingReferenceIds)
            //             ->delete();
            //     }

            //     // Update or create references
            //     foreach ($validatedData['references'] as $referenceData) {
            //         if (!empty($referenceData['id'])) {
            //             // Update existing reference
            //             $testAttribute->attributeReferences()
            //                 ->where('id', $referenceData['id'])
            //                 ->update([
            //                     'gender' => $referenceData['gender'] ?? null,
            //                     'min_age' => $referenceData['min_age'] ?? null,
            //                     'max_age' => $referenceData['max_age'] ?? null,
            //                     'is_pregnant' => $referenceData['is_pregnant'] ?? null,
            //                     'lower_operator' => $referenceData['lower_operator'] ?? null,
            //                     'lower_limit' => $referenceData['lower_limit'] ?? null,
            //                     'upper_operator' => $referenceData['upper_operator'] ?? null,
            //                     'upper_limit' => $referenceData['upper_limit'] ?? null,
            //                     'reference_text' => $referenceData['reference_text'] ?? null,
            //                 ]);
            //         } else {
            //             // Create new reference
            //             $testAttribute->attributeReferences()->create([
            //                 'gender' => $referenceData['gender'] ?? null,
            //                 'min_age' => $referenceData['min_age'] ?? null,
            //                 'max_age' => $referenceData['max_age'] ?? null,
            //                 'is_pregnant' => $referenceData['is_pregnant'] ?? null,
            //                 'lower_operator' => $referenceData['lower_operator'] ?? null,
            //                 'lower_limit' => $referenceData['lower_limit'] ?? null,
            //                 'upper_operator' => $referenceData['upper_operator'] ?? null,
            //                 'upper_limit' => $referenceData['upper_limit'] ?? null,
            //                 'reference_text' => $referenceData['reference_text'] ?? null,
            //             ]);
            //         }
            //     }
            // } else {
            //     // If no references are submitted but the attribute had some, delete them
            //     if (method_exists($testAttribute, 'references') && $testAttribute->attributeReferences()->exists()) {
            //         $testAttribute->attributeReferences()->delete();
            //     }
            // }

            // // Commit the transaction
            // DB::commit();

            Toastr::success(translate('Test Attribute Updated successfully!'));
            return redirect()->route('admin.test_attribute.list');
        } catch (\Exception $e) {
            // Rollback the transaction on error
            DB::rollBack();
            Toastr::error(translate($e->getMessage()));
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        try {
            $attribute = TestAttribute::findOrFail($id);
            $attribute->delete();
            Toastr::success(translate('Test Attribute Deleted Successfully!'));
            return back();
        } catch (\Exception $e) {
            Toastr::success($e->getMessage());
        }
    }
}
