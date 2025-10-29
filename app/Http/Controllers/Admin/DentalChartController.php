<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\DentalChart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Storage;

class DentalChartController extends Controller
{
    public function __construct(private DentalChart $dentalChart)
    {
        $this->middleware('checkAdminPermission:dental_chart.list,list')->only(['list']);
        $this->middleware('checkAdminPermission:dental_chart.add-new,index')->only(['index', 'store']);
        $this->middleware('checkAdminPermission:dental_chart.edit,edit')->only(['edit']);
        $this->middleware('checkAdminPermission:dental_chart.update,update')->only(['update']);
        $this->middleware('checkAdminPermission:dental_chart.delete,delete')->only(['destroy']);
    }

    public function index()
    {
        return view('admin-views.dental-chart.index');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'visit_id' => 'required|exists:visits,id',
            'chart_type' => 'required|in:odontogram,periodontal,treatment_plan,clinical_drawing,image_annotation',
            'title' => 'nullable|string|max:255',
            'chart_data' => 'nullable|json',
            'tooth_data' => 'nullable|json',
            'image_path' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $data = $request->all();
            $data['created_by'] = auth('admin')->id();

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                $imagePath = $request->file('image')->store('dental_charts', 'public');
                $data['image_path'] = $imagePath;
            }

            // Ensure chart_data and tooth_data are properly handled
            if ($request->has('chart_data') && is_string($request->chart_data)) {
                $data['chart_data'] = $request->chart_data;
            } elseif ($request->has('chart_data')) {
                $data['chart_data'] = json_encode($request->chart_data);
            }

            if ($request->has('tooth_data') && is_string($request->tooth_data)) {
                $data['tooth_data'] = $request->tooth_data;
            } elseif ($request->has('tooth_data')) {
                $data['tooth_data'] = json_encode($request->tooth_data);
            }

            $dentalChart = $this->dentalChart->create($data);

            return response()->json([
                'success' => true,
                'message' => 'Dental chart created successfully',
                'visit_id' => $request->visit_id,
                'data' => $dentalChart
            ], 200);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error creating dental chart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        try {
            $dentalChart = $this->dentalChart->findOrFail($id);

            // Convert array back to JSON string for JavaScript compatibility
            $data = $dentalChart->toArray();
            if (is_array($data['chart_data'])) {
                $data['chart_data'] = json_encode($data['chart_data']);
            }
            if (is_array($data['tooth_data'])) {
                $data['tooth_data'] = json_encode($data['tooth_data']);
            }

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error retrieving dental chart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'chart_type' => 'sometimes|in:odontogram,periodontal,treatment_plan,clinical_drawing,image_annotation',
            'title' => 'nullable|string|max:255',
            'chart_data' => 'nullable|json',
            'tooth_data' => 'nullable|json',
            'image_path' => 'nullable|string',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $dentalChart = $this->dentalChart->findOrFail($id);
            $data = $request->all();

            // Handle image upload if provided
            if ($request->hasFile('image')) {
                // Delete old image if exists
                if ($dentalChart->image_path) {
                    Storage::disk('public')->delete($dentalChart->image_path);
                }
                $imagePath = $request->file('image')->store('dental_charts', 'public');
                $data['image_path'] = $imagePath;
            }

            // Ensure chart_data and tooth_data are properly handled
            if ($request->has('chart_data') && is_string($request->chart_data)) {
                $data['chart_data'] = $request->chart_data;
            } elseif ($request->has('chart_data')) {
                $data['chart_data'] = json_encode($request->chart_data);
            }

            if ($request->has('tooth_data') && is_string($request->tooth_data)) {
                $data['tooth_data'] = $request->tooth_data;
            } elseif ($request->has('tooth_data')) {
                $data['tooth_data'] = json_encode($request->tooth_data);
            }

            $dentalChart->update($data);

            return response()->json([
                'success' => true,
                'message' => 'Dental chart updated successfully',
                'visit_id' => $dentalChart->visit_id
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error updating dental chart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $dentalChart = $this->dentalChart->findOrFail($id);
            $visitId = $dentalChart->visit_id;

            // Delete associated image if exists
            if ($dentalChart->image_path) {
                Storage::disk('public')->delete($dentalChart->image_path);
            }

            $dentalChart->delete();

            return response()->json([
                'success' => true,
                'message' => 'Dental chart deleted successfully',
                'visit_id' => $visitId
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error deleting dental chart: ' . $e->getMessage()
            ], 500);
        }
    }

    public function list()
    {
        // Implementation for list view if needed
    }
}
