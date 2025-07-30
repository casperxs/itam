<?php // app/Http/Controllers/DashboardController.php
namespace App\Http\Controllers;

use App\Models\Equipment;
use App\Models\Assignment;
use App\Models\MaintenanceRecord;
use App\Models\Contract;
use App\Models\EmailTicket;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'total_equipment' => Equipment::count(),
            'assigned_equipment' => Equipment::where('status', 'assigned')->count(),
            'available_equipment' => Equipment::where('status', 'available')->count(),
            'maintenance_equipment' => Equipment::where('status', 'maintenance')->count(),
            'pending_maintenance' => MaintenanceRecord::where('status', 'scheduled')->count(),
            'expired_warranties' => Equipment::whereDate('warranty_end_date', '<', now())->count(),
            'expiring_contracts' => Contract::expiringSoon(30)->count(),
            'pending_tickets' => EmailTicket::where('status', 'pending')->count(),
        ];

        $recent_assignments = Assignment::with(['equipment', 'itUser', 'assignedBy'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get();

        $upcoming_maintenance = MaintenanceRecord::with(['equipment', 'performedBy'])
            ->where('status', 'scheduled')
            ->where('scheduled_date', '>=', now())
            ->orderBy('scheduled_date')
            ->limit(5)
            ->get();

        $expiring_warranties = Equipment::with(['equipmentType', 'supplier'])
            ->whereDate('warranty_end_date', '<=', now()->addDays(30))
            ->whereDate('warranty_end_date', '>=', now())
            ->orderBy('warranty_end_date')
            ->limit(5)
            ->get();

        $totalEquipment = Equipment::count();
        $availableEquipment = Equipment::where('status', 'available')->count();
        $expiringSoon = Equipment::whereDate('warranty_end_date', '<=', now()->addDays(30))
            ->whereDate('warranty_end_date', '>=', now())
            ->count();
        $totalUsers = \App\Models\ItUser::count();
        $recentEquipment = Equipment::orderBy('created_at', 'desc')->limit(5)->get();

        return view('dashboard.index', compact(
            'stats', 'recent_assignments', 'upcoming_maintenance', 'expiring_warranties',
            'totalEquipment', 'availableEquipment', 'expiringSoon', 'totalUsers', 'recentEquipment'
        ));
    }
}
