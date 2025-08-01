<?php // app/Http/Controllers/ReportController.php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Assignment;
use App\Models\MaintenanceRecord;
use App\Models\Contract;
use App\Models\ItUser;
use App\Services\ReportService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    protected $reportService;

    public function __construct(ReportService $reportService)
    {
        $this->reportService = $reportService;
    }

    public function index()
    {
        $totalEquipment = Equipment::count();
        $activeAssignments = Assignment::whereNull('returned_at')->count();
        $pendingMaintenance = MaintenanceRecord::where('status', 'scheduled')->count();
        $expiredContracts = Contract::where('end_date', '<', Carbon::now())->count();

        return view('reports.index', compact('totalEquipment', 'activeAssignments', 'pendingMaintenance', 'expiredContracts'));
    }

    public function equipment(Request $request)
    {
        $filters = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'status' => 'nullable|string',
            'type' => 'nullable|exists:equipment_types,id',
            'supplier' => 'nullable|exists:suppliers,id',
        ]);

        $data = $this->reportService->generateEquipmentReport($filters);

        if ($request->has('format') && in_array($request->format, ['pdf', 'excel'])) {
            return $this->reportService->exportEquipmentReport($data, $request->format);
        }

        return view('reports.equipment', compact('data', 'filters'));
    }

    public function assignments(Request $request)
    {
        $filters = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'user' => 'nullable|exists:it_users,id',
            'department' => 'nullable|string',
            'status' => 'nullable|in:active,returned',
        ]);

        $data = $this->reportService->generateAssignmentReport($filters);

        if ($request->has('format') && in_array($request->format, ['pdf', 'excel'])) {
            return $this->reportService->exportAssignmentReport($data, $request->format);
        }

        return view('reports.assignments', compact('data', 'filters'));
    }

    public function maintenance(Request $request)
    {
        $filters = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'type' => 'nullable|in:preventive,corrective,update',
            'status' => 'nullable|in:scheduled,in_progress,completed,cancelled',
            'technician' => 'nullable|exists:users,id',
        ]);

        $data = $this->reportService->generateMaintenanceReport($filters);

        if ($request->has('format') && in_array($request->format, ['pdf', 'excel'])) {
            return $this->reportService->exportMaintenanceReport($data, $request->format);
        }

        return view('reports.maintenance', compact('data', 'filters'));
    }

    public function contracts(Request $request)
    {
        $filters = $request->validate([
            'date_from' => 'nullable|date',
            'date_to' => 'nullable|date',
            'status' => 'nullable|in:active,expired,cancelled',
            'supplier' => 'nullable|exists:suppliers,id',
        ]);

        $data = $this->reportService->generateContractReport($filters);

        if ($request->has('format') && in_array($request->format, ['pdf', 'excel'])) {
            return $this->reportService->exportContractReport($data, $request->format);
        }

        return view('reports.contracts', compact('data', 'filters'));
    }

    public function dashboard()
    {
        $data = [
            'equipment_by_status' => Equipment::selectRaw('status, COUNT(*) as count')
                ->groupBy('status')
                ->get(),
            'equipment_by_type' => Equipment::join('equipment_types', 'equipment.equipment_type_id', '=', 'equipment_types.id')
                ->selectRaw('equipment_types.name, COUNT(*) as count')
                ->groupBy('equipment_types.name')
                ->get(),
            'assignments_by_month' => Assignment::selectRaw('YEAR(assigned_at) as year, MONTH(assigned_at) as month, COUNT(*) as count')
                ->where('assigned_at', '>=', Carbon::now()->subYear())
                ->groupBy('year', 'month')
                ->orderBy('year')
                ->orderBy('month')
                ->get(),
            'maintenance_by_type' => MaintenanceRecord::selectRaw('type, COUNT(*) as count')
                ->groupBy('type')
                ->get(),
            'top_users_by_assignments' => ItUser::withCount('assignments')
                ->orderBy('assignments_count', 'desc')
                ->limit(10)
                ->get(),
        ];

        return view('reports.dashboard', compact('data'));
    }
}
