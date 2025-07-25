<?php // app/Http/Controllers/UserDocumentController.php
namespace App\Http\Controllers;

use App\Models\UserDocument;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class UserDocumentController extends Controller
{
    public function download(UserDocument $document)
    {
        if (!Storage::disk('private')->exists($document->file_path)) {
            return redirect()->back()->with('error', 'Archivo no encontrado.');
        }

        return Storage::disk('private')->download($document->file_path, $document->document_name);
    }

    public function destroy(UserDocument $document)
    {
        Storage::disk('private')->delete($document->file_path);
        $document->delete();

        return redirect()->back()->with('success', 'Documento eliminado exitosamente.');
    }

    public function markSigned(UserDocument $document)
    {
        $document->update([
            'has_signature' => true,
            'signature_type' => request('signature_type', 'physical')
        ]);

        return redirect()->back()->with('success', 'Documento marcado como firmado.');
    }
}