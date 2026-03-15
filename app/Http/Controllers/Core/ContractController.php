<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Client;
use App\Models\Contract;
use Illuminate\Http\Request;

class ContractController extends Controller
{
    public function index()
    {
        $contracts = Contract::with('client')->latest()->paginate(10);
        return view('core.contracts.index', compact('contracts'));
    }

    public function create()
    {
        $clients = Client::orderBy('name')->get();
        return view('core.contracts.create', compact('clients'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'client_id' => ['required','exists:clients,id'],
            'draft_pdf' => ['required','file','mimes:pdf,doc,docx','max:20480'],
        ]);

        $path = $request->file('draft_pdf')->store('contracts/drafts', 'public');

        $contract = Contract::create([
            'client_id' => $data['client_id'],
            'draft_pdf_path' => $path,
            'created_by' => auth()->id(),
        ]);

        return redirect()->route('core.contracts.show', $contract)->with('success','Draft contract uploaded.');
    }

    public function show(Contract $contract)
    {
        $contract->load('client');
        return view('core.contracts.show', compact('contract'));
    }

    public function uploadSigned(Request $request, Contract $contract)
    {
        $data = $request->validate([
            'signed_pdf' => ['required','file','mimes:pdf,doc,docx','max:20480'],
        ]);

        $path = $request->file('signed_pdf')->store('contracts/signed', 'public');

        $contract->update([
            'signed_pdf_path' => $path,
            'signed_at' => now(),
        ]);

        return back()->with('success','Signed contract uploaded.');
    }
}