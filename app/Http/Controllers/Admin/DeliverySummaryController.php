<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\DeliverySummary;
use Illuminate\Support\Facades\DB;

class DeliverySummaryController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'pregnancy_id' => 'required|exists:pregnancies,id',
            'visit_id' => 'required|exists:visits,id|unique:delivery_summaries,visit_id',
            'delivered_by' => 'nullable|exists:admins,id',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'delivery_mode' => 'nullable|in:SVD,C-Section,SVD Vacuum,SVD Forceps',
            'delivery_outcome' => 'nullable|in:Alive,Stillbirth',
            'remarks' => 'nullable|string',
        ]);

        DB::beginTransaction();
        try {
            // Save delivery summary
            $summary = DeliverySummary::create($request->all());

            // Get related pregnancy
            $pregnancy = $summary->pregnancy;

            // Update pregnancy status based on delivery_outcome
            if ($request->delivery_outcome === 'Alive') {
                $pregnancy->status = 'completed';
            } elseif ($request->delivery_outcome === 'Stillbirth') {
                $pregnancy->status = 'aborted';
            }

            $pregnancy->save();

            DB::commit();
            return response()->json(['message' => 'Delivery summary saved successfully.', 'visit_id' => $summary->visit_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'message' => 'Failed to save delivery summary.' . $e->getMessage(),
            ], 500);
        }
    }


    public function update(Request $request, $id)
    {
        $request->validate([
            'delivered_by' => 'nullable|exists:admins,id',
            'date' => 'nullable|date',
            'time' => 'nullable',
            'delivery_mode' => 'nullable|in:SVD,C-Section,SVD Vacuum,SVD Forceps',
            'delivery_outcome' => 'nullable|in:Alive,Stillbirth',
            'remarks' => 'nullable|string',
            // other fields validations as needed
        ]);


        try {
            $deliverySummary = DeliverySummary::findOrFail($id);
            DB::beginTransaction();
            $checkboxes = [
                'mrp',
                'laceration_repair',
                'misoprostol',
                'episiotomy',
                'ruptured_uterus_repaired',
                'hysterectomy',
                'referred_for_support'
            ];

            $data = $request->all();

            foreach ($checkboxes as $field) {
                $data[$field] = $request->has($field) ? 1 : 0;
            }

            // Update delivery summary
            $deliverySummary->update($data);

            // Update pregnancy status if delivery outcome changed
            $pregnancy = $deliverySummary->pregnancy;
            if ($request->delivery_outcome === 'Alive') {
                $pregnancy->status = 'completed';
            } elseif ($request->delivery_outcome === 'Stillbirth') {
                $pregnancy->status = 'aborted';
            }
            $pregnancy->save();

            DB::commit();

            return response()->json(['message' => 'Delivery summary updated successfully.', 'visit_id' => $deliverySummary->visit_id], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Failed to update delivery summary.' . $e->getMessage()], 500);
        }
    }
}
