<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PrenatalVisitHistorySheet;
class PrenatalVisitHistoryController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'history' => 'nullable|string',
            'physical_findings' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        try {
            $record = PrenatalVisitHistorySheet::create($validated);

            return response()->json([
                'message' => 'History created successfully.',
                'visit_id' => $record->visit_id,
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to create: ' . $e->getMessage()], 500);
        }
    }


    public function edit($id)
    {
        $record = PrenatalVisitHistorySheet::findOrFail($id);
        return response()->json($record);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'history' => 'nullable|string',
            'physical_findings' => 'nullable|string',
            'progress_notes' => 'nullable|string',
            'remarks' => 'nullable|string',
        ]);

        try {
            $record = PrenatalVisitHistorySheet::findOrFail($id);
            $record->update($validated);

            return response()->json([
                'message' => 'History updated successfully.',
                'data' => $record
            ], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Failed to update: ' . $e->getMessage()], 500);
        }
    }
}
