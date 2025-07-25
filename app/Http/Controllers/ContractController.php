<?php // app/Http/Controllers/ContractController.php
namespace App\Http\Controllers;

use App\Models\Contract;
use App\Models\Supplier;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ContractController extends Controller
{
    public function index(Request $request)
    {
        $query = Contract::with('supplier');

        if ($request->has('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('contract_number', 'like', "%{$search}%")
                  ->orWhere('service_description', 'like', "%{$search}%")
                  ->orWhereHas('supplier', function($sq) use ($search) {
                      $sq->where('name', 'like', "%{$search}%");
                  });
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $contracts = $query->orderBy('end_date')->paginate(15);
        $expiringContracts = Contract::expiringSoon(30)->count();

        return view('contracts.index', compact('contracts', 'expiringContracts'));
    }

    public function create()
    {
        $suppliers = Supplier::all();
        return view('contracts.create', compact('suppliers'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'required|string|unique:contracts',
            'service_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_cost' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'status' => 'required|in:active,expired,cancelled',
            'alert_days_before' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'contract_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('contract_file')) {
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'private');
        }

        Contract::create($validated);

        return redirect()->route('contracts.index')
            ->with('success', 'Contrato creado exitosamente.');
    }

    public function show(Contract $contract)
    {
        $contract->load('supplier');
        return view('contracts.show', compact('contract'));
    }

    public function edit(Contract $contract)
    {
        $suppliers = Supplier::all();
        return view('contracts.edit', compact('contract', 'suppliers'));
    }

    public function update(Request $request, Contract $contract)
    {
        $validated = $request->validate([
            'supplier_id' => 'required|exists:suppliers,id',
            'contract_number' => 'required|string|unique:contracts,contract_number,' . $contract->id,
            'service_description' => 'required|string',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
            'monthly_cost' => 'nullable|numeric',
            'total_cost' => 'nullable|numeric',
            'status' => 'required|in:active,expired,cancelled',
            'alert_days_before' => 'required|integer|min:1',
            'notes' => 'nullable|string',
            'contract_file' => 'nullable|file|mimes:pdf|max:10240',
        ]);

        if ($request->hasFile('contract_file')) {
            if ($contract->contract_file) {
                Storage::disk('private')->delete($contract->contract_file);
            }
            $validated['contract_file'] = $request->file('contract_file')->store('contracts', 'private');
        }

        $contract->update($validated);

        return redirect()->route('contracts.show', $contract)
            ->with('success', 'Contrato actualizado exitosamente.');
    }

    public function destroy(Contract $contract)
    {
        if ($contract->contract_file) {
            Storage::disk('private')->delete($contract->contract_file);
        }

        $contract->delete();

        return redirect()->route('contracts.index')
            ->with('success', 'Contrato eliminado exitosamente.');
    }

    public function downloadFile(Contract $contract)
    {
        if (!$contract->contract_file || !Storage::disk('private')->exists($contract->contract_file)) {
            return redirect()->back()->with('error', 'Archivo de contrato no encontrado.');
        }

        return Storage::disk('private')->download($contract->contract_file);
    }

    public function renewContract(Contract $contract)
    {
        $newContract = $contract->replicate();
        $newContract->start_date = $contract->end_date->addDay();
        $newContract->end_date = $newContract->start_date->copy()->addYear();
        $newContract->status = 'active';
        $newContract->contract_number = $contract->contract_number . '-R';
        
        return view('contracts.create', [
            'suppliers' => Supplier::all(),
            'contract' => $newContract,
            'isRenewal' => true
        ]);
    }
}
