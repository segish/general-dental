<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MedicalRecordField;
use App\Models\MedicalRecordFieldOption;
use Illuminate\Http\Request;
use Brian2694\Toastr\Facades\Toastr;
use App\CentralLogics\Helpers;
use Illuminate\Contracts\View\View;
use Illuminate\Contracts\View\Factory;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Support\Facades\DB;

class MedicalRecordFieldController extends Controller
{
    function __construct(
        private MedicalRecordField $medicalRecordField
    ) {
        $this->middleware('checkAdminPermission:medical_record_field.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:medical_record_field.add-new,index')->only(['index']);
        $this->middleware('checkAdminPermission:medical_record_field.add-new,store')->only(['store']);
        $this->middleware('checkAdminPermission:medical_record_field.edit,edit')->only(['edit']);
        $this->middleware('checkAdminPermission:medical_record_field.update,update')->only(['update']);
        $this->middleware('checkAdminPermission:medical_record_field.delete,destroy')->only(['destroy']);
    }

    public function index()
    {
        return view('admin-views.medical-record-field.index');
    }

    public function list(Request $request): Factory|View|Application
    {
        $query_param = [];
        $search = $request['search'];
        if ($request->has('search')) {
            $key = explode(' ', $request['search']);
            $query = $this->medicalRecordField->where(function ($q) use ($key) {
                foreach ($key as $value) {
                    $q->orWhere('name', 'like', "%{$value}%")
                        ->orWhere('short_code', 'like', "%{$value}%")
                        ->orWhere('field_type', 'like', "%{$value}%");
                }
            })->latest();
            $query_param = ['search' => $request['search']];
        } else {
            $query = $this->medicalRecordField->withCount('options')->ordered();
        }
        $fields = $query->paginate(Helpers::pagination_limit())->appends($query_param);
        return view('admin-views.medical-record-field.list', compact('fields', 'search'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:255|unique:medical_record_fields,short_code',
            'field_type' => 'required|in:text,textarea,select,multiselect,checkbox',
            'is_multiple' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'options' => 'required_if:field_type,select,multiselect,checkbox|array',
            'options.*.option_value' => 'required_with:options|string|max:255',
            'options.*.option_label' => 'required_with:options|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $field = MedicalRecordField::create([
                'name' => $request->name,
                'short_code' => $request->short_code,
                'field_type' => $request->field_type,
                'is_multiple' => $request->field_type == 'multiselect' || $request->field_type == 'checkbox' ? true : ($request->is_multiple ?? false),
                'is_required' => $request->is_required ?? false,
                'order' => $request->order ?? 0,
                'status' => $request->status ?? true,
            ]);

            // Add options if field type requires them
            if (in_array($request->field_type, ['select', 'multiselect', 'checkbox']) && $request->has('options')) {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['option_value']) && !empty($option['option_label'])) {
                        MedicalRecordFieldOption::create([
                            'medical_record_field_id' => $field->id,
                            'option_value' => $option['option_value'],
                            'option_label' => $option['option_label'],
                            'order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();
            Toastr::success('Medical record field added successfully!');
            return redirect()->route('admin.medical_record_field.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Error: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function edit($id)
    {
        $field = MedicalRecordField::with('options')->findOrFail($id);
        return view('admin-views.medical-record-field.edit', compact('field'));
    }

    public function update(Request $request, $id)
    {
        $field = MedicalRecordField::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'short_code' => 'required|string|max:255|unique:medical_record_fields,short_code,' . $id,
            'field_type' => 'required|in:text,textarea,select,multiselect,checkbox',
            'is_multiple' => 'nullable|boolean',
            'is_required' => 'nullable|boolean',
            'order' => 'nullable|integer|min:0',
            'status' => 'nullable|boolean',
            'options' => 'required_if:field_type,select,multiselect,checkbox|array',
            'options.*.option_value' => 'required_with:options|string|max:255',
            'options.*.option_label' => 'required_with:options|string|max:255',
        ]);

        try {
            DB::beginTransaction();

            $field->update([
                'name' => $request->name,
                'short_code' => $request->short_code,
                'field_type' => $request->field_type,
                'is_multiple' => $request->field_type == 'multiselect' || $request->field_type == 'checkbox' ? true : ($request->is_multiple ?? false),
                'is_required' => $request->is_required ?? false,
                'order' => $request->order ?? 0,
                'status' => $request->status ?? true,
            ]);

            // Delete existing options
            $field->options()->delete();

            // Add new options if field type requires them
            if (in_array($request->field_type, ['select', 'multiselect', 'checkbox']) && $request->has('options')) {
                foreach ($request->options as $index => $option) {
                    if (!empty($option['option_value']) && !empty($option['option_label'])) {
                        MedicalRecordFieldOption::create([
                            'medical_record_field_id' => $field->id,
                            'option_value' => $option['option_value'],
                            'option_label' => $option['option_label'],
                            'order' => $index,
                        ]);
                    }
                }
            }

            DB::commit();
            Toastr::success('Medical record field updated successfully!');
            return redirect()->route('admin.medical_record_field.list');
        } catch (\Exception $e) {
            DB::rollBack();
            Toastr::error('Error: ' . $e->getMessage());
            return back()->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $field = MedicalRecordField::findOrFail($id);

            // Check if field has any values
            if ($field->values()->count() > 0) {
                Toastr::error('Cannot delete field. It has associated values in medical records.');
                return back();
            }

            $field->delete();
            Toastr::success('Medical record field deleted successfully!');
        } catch (\Exception $e) {
            Toastr::error('Error: ' . $e->getMessage());
        }
        return back();
    }
}
