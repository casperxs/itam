<?php // app/Http/Controllers/BulkImportController.php
namespace App\Http\Controllers;

use App\Models\BulkImport;
use App\Services\BulkImportService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class BulkImportController extends Controller
{
    protected $importService;

    public function __construct(BulkImportService $importService)
    {
        $this->importService = $importService;
    }

    public function index()
    {
        $imports = BulkImport::with('importedBy')
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        return view('bulk-imports.index', compact('imports'));
    }

    public function create()
    {
        return view('bulk-imports.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'import_type' => 'required|in:equipment,users',
            'file' => 'required|file|mimes:txt|max:2048',
        ]);

        $filePath = $request->file('file')->store('bulk-imports', 'private');
        
        $import = BulkImport::create([
            'file_name' => $request->file('file')->getClientOriginalName(),
            'file_path' => $filePath,
            'import_type' => $validated['import_type'],
            'status' => 'pending',
            'imported_by' => Auth::id(),
        ]);

        // Procesar importación en segundo plano
        $this->importService->processImport($import);

        return redirect()->route('bulk-imports.show', $import)
            ->with('success', 'Importación iniciada exitosamente.');
    }

    public function show(BulkImport $bulkImport)
    {
        return view('bulk-imports.show', compact('bulkImport'));
    }

    public function downloadTemplate($type)
    {
        $templates = [
            'equipment' => 'equipment-template.txt',
            'users' => 'users-template.txt',
        ];

        if (!isset($templates[$type])) {
            abort(404);
        }

        $content = $this->importService->generateTemplate($type);
        
        return response($content)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $templates[$type] . '"');
    }
}
