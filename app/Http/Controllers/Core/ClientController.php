<?php

namespace App\Http\Controllers\Core;

use App\Http\Controllers\Controller;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    public function index()
    {
        $clients = Client::latest()->get();
        return view('core.clients.index', compact('clients'));
    }

    public function create()
    {
        return view('core.clients.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'nullable|email',
            'contact_person' => 'nullable|string|max:255',
            'phone' => 'nullable|string|max:50',
        ]);

        Client::create($request->all());

        return redirect()->route('core.clients.index')
            ->with('success', 'Client created successfully.');
    }

    public function show(Client $client)
    {
        return view('core.clients.show', compact('client'));
    }

    public function edit(Client $client)
    {
        return view('core.clients.edit', compact('client'));
    }

    public function update(Request $request, Client $client)
    {
        $request->validate([
            'name' => 'required|string|max:255',
        ]);

        $client->update($request->all());

        return redirect()->route('core.clients.index')
            ->with('success', 'Client updated successfully.');
    }

    public function destroy(Client $client)
    {
        $client->delete();

        return redirect()->route('core.clients.index')
            ->with('success', 'Client deleted successfully.');
    }
}