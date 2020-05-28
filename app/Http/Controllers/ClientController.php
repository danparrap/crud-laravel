<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\Request;

class ClientController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('client.index', [
            'clients' => Client::simplePaginate(10),
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('client.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|email|max:50|confirmed|unique:clients',
            'name' => 'required|string|max:50',
            'birthday' => 'required|date|before:tomorrow',
        ]);

        Client::create($fields);

        return redirect()->route('clients.index')->with('status', 'Su registro fue realizado satisfactoriamente');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function show(Client $client)
    {
        return view('client.show', [
            'client' => $client
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function edit(Client $client)
    {
        return view('client.edit', [
            'client' => $client,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Client $client)
    {
        $fields = $request->validate([
            'email' => 'required|email|max:50|confirmed|unique:clients,email,'.$client->id,
            'name' => 'required|string|max:50',
            'birthday' => 'required|date|before:tomorrow',
        ]);

        $client->fill($fields);

        if($client->isClean()) {
            return redirect()->route('clients.index')->with('status', 'Su registro no fue necesario actualizarlo');
        }

        $client->save();

        return redirect()->route('clients.index')->with('status', 'Su registro fue actualizado satisfactoriamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Client  $client
     * @return \Illuminate\Http\Response
     */
    public function destroy(Client $client)
    {
        $client->delete();
        return redirect()->route('clients.index')->with('status', 'Su registro fue eliminado satisfactoriamente');
    }
}
