<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\VisitDocument;
use App\Models\Visit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class VisitDocumentController extends Controller
{
    public function __construct()
    {
        $this->middleware('checkAdminPermission:visit_document.list,list')->only(['index']);
        $this->middleware('checkAdminPermission:visit_document.store,store')->only(['store']);
        $this->middleware('checkAdminPermission:visit_document.update,update')->only(['update']);
        $this->middleware('checkAdminPermission:visit_document.delete,destroy')->only(['destroy']);
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $visitId = $request->get('visit_id');

        if (!$visitId) {
            return response()->json(['error' => 'Visit ID is required'], 400);
        }

        $visit = Visit::with('visitDocuments.uploadedBy')->findOrFail($visitId);

        return response()->json([
            'success' => true,
            'documents' => $visit->visitDocuments
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'visit_id' => 'required|exists:visits,id',
            'files' => 'required|array|min:1',
            'files.*' => 'file|max:10240', // 10MB max per file
            'note' => 'nullable|string|max:1000'
        ]);

        $uploadedDocuments = [];
        $errors = [];

        foreach ($request->file('files') as $file) {
            try {
                // Generate unique filename
                $extension = $file->getClientOriginalExtension();
                $filename = Str::uuid() . '.' . $extension;

                // Store file
                $path = $file->storeAs('visit-documents', $filename, 'public');

                // Determine file type
                $fileType = $this->getFileType($file->getMimeType());

                // Create document record
                $document = VisitDocument::create([
                    'visit_id' => $request->visit_id,
                    'document_path' => $path,
                    'original_name' => $file->getClientOriginalName(),
                    'file_type' => $fileType,
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'note' => $request->note,
                    'uploaded_by' => auth('admin')->id()
                ]);

                $uploadedDocuments[] = $document->load('uploadedBy');
            } catch (\Exception $e) {
                $errors[] = [
                    'file' => $file->getClientOriginalName(),
                    'error' => $e->getMessage()
                ];
            }
        }

        if (count($errors) > 0 && count($uploadedDocuments) === 0) {
            return response()->json([
                'success' => false,
                'message' => 'Failed to upload documents',
                'errors' => $errors
            ], 400);
        }

        $message = count($uploadedDocuments) . ' document(s) uploaded successfully';
        if (count($errors) > 0) {
            $message .= ', ' . count($errors) . ' failed';
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'documents' => $uploadedDocuments,
            'errors' => $errors
        ]);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $document = VisitDocument::with(['visit', 'uploadedBy'])->findOrFail($id);

        return response()->json([
            'success' => true,
            'document' => $document
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'note' => 'nullable|string|max:1000'
        ]);

        $document = VisitDocument::findOrFail($id);
        $document->update([
            'note' => $request->note
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Document updated successfully',
            'document' => $document->load('uploadedBy')
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $document = VisitDocument::findOrFail($id);

        // Delete file from storage
        if (Storage::disk('public')->exists($document->document_path)) {
            Storage::disk('public')->delete($document->document_path);
        }

        $document->delete();

        return response()->json([
            'success' => true,
            'message' => 'Document deleted successfully'
        ]);
    }

    /**
     * Download the document file
     */
    public function download($id)
    {
        $document = VisitDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File not found');
        }

        return response()->download(
            storage_path('app/public/' . $document->document_path),
            $document->original_name
        );
    }

    /**
     * View the document file
     */
    public function view($id)
    {
        $document = VisitDocument::findOrFail($id);

        if (!Storage::disk('public')->exists($document->document_path)) {
            abort(404, 'File not found');
        }

        return response()->file(storage_path('app/public/' . $document->document_path));
    }

    /**
     * Determine file type based on MIME type
     */
    private function getFileType($mimeType)
    {
        if (str_starts_with($mimeType, 'image/')) {
            return 'image';
        } elseif ($mimeType === 'application/pdf') {
            return 'pdf';
        } elseif (in_array($mimeType, [
            'application/msword',
            'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'text/plain'
        ])) {
            return 'document';
        } else {
            return 'other';
        }
    }
}
