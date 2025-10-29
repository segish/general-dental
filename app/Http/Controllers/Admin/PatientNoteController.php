<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PatientNote;
class PatientNoteController extends Controller
{
    //

    public function store(Request $request){

        $request->validate([
            'patient_id' => 'required|exists:patients,id',
            'bed_id' => 'required|exists:beds,id',
            'admission_note' => 'nullable|string',
            'progress_note_daily' => 'nullable|string',
            'summary_discharge_note' => 'nullable|string',
            'temperature' => 'nullable|numeric',
            'blood_pressure' => 'nullable|string',
            'heart_rate' => 'nullable|integer',
            'respiratory_rate' => 'nullable|integer',
            'additional_notes' => 'nullable|string',
        ]);
    
        // Create a new patient note
        $patientNote = new PatientNote([
            'patient_id' => $request->input('patient_id'),
            'bed_id' => $request->input('bed_id'),
            'admission_note' => $request->input('admission_note'),
            'progress_note_daily' => $request->input('progress_note_daily'),
            'summary_discharge_note' => $request->input('summary_discharge_note'),
            'temperature' => $request->input('temperature'),
            'blood_pressure' => $request->input('blood_pressure'),
            'heart_rate' => $request->input('heart_rate'),
            'respiratory_rate' => $request->input('respiratory_rate'),
            'additional_notes' => $request->input('additional_notes'),
        ]);
    
        // Save the patient note
        $patientNote->save();

        return response()->json([], 200);

    }
}
