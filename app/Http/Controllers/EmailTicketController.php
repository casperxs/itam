<?php // app/Http/Controllers/EmailTicketController.php
namespace App\Http\Controllers;

use App\Models\EmailTicket;
use App\Models\User;
use App\Models\ItUser;
use App\Models\Equipment;
use Illuminate\Http\Request;

class EmailTicketController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailTicket::with(['assignedTo', 'itUser', 'equipment']);

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                  ->orWhere('from_email', 'like', "%{$search}%")
                  ->orWhere('from_name', 'like', "%{$search}%");
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $tickets = $query->orderBy('received_at', 'desc')->paginate(15);
        $pendingCount = EmailTicket::where('status', 'pending')->count();

        return view('email-tickets.index', compact('tickets', 'pendingCount'));
    }

    public function show(EmailTicket $emailTicket)
    {
        $emailTicket->load(['assignedTo', 'itUser', 'equipment']);
        return view('email-tickets.show', compact('emailTicket'));
    }

    public function assign(Request $request, EmailTicket $emailTicket)
    {
        $validated = $request->validate([
            'assigned_to' => 'required|exists:users,id',
            'it_user_id' => 'nullable|exists:it_users,id',
            'equipment_id' => 'nullable|exists:equipment,id',
        ]);

        $emailTicket->update([
            ...$validated,
            'status' => 'in_progress',
        ]);

        return redirect()->route('email-tickets.show', $emailTicket)
            ->with('success', 'Ticket asignado exitosamente.');
    }

    public function resolve(Request $request, EmailTicket $emailTicket)
    {
        $validated = $request->validate([
            'resolution_notes' => 'required|string',
        ]);

        $emailTicket->update([
            'resolution_notes' => $validated['resolution_notes'],
            'status' => 'resolved',
        ]);

        return redirect()->route('email-tickets.show', $emailTicket)
            ->with('success', 'Ticket resuelto exitosamente.');
    }

    public function close(EmailTicket $emailTicket)
    {
        $emailTicket->update(['status' => 'closed']);

        return redirect()->route('email-tickets.index')
            ->with('success', 'Ticket cerrado exitosamente.');
    }
}

