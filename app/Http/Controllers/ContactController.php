<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreContactRequest;
use App\Models\Contact;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $perPage = in_array(request()->integer('per_page'), [10, 25, 50], true) ? request()->integer('per_page') : 10;
        $contacts = Contact::query()
            ->when(request('search'), fn ($query, $search) => $query->where('name', 'like', '%'.$search.'%'))
            ->when(request('type'), fn ($query, $type) => $query->where('type', $type))
            ->latest()->paginate($perPage)->withQueryString();

        return view('contacts.index', compact('contacts'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('contacts.form', ['contact' => new Contact(['type' => request('type', 'customer')])]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreContactRequest $request): RedirectResponse
    {
        Contact::create($request->validated());

        return to_route('contacts.index')->with('success', 'Kontak berhasil ditambahkan.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Contact $contact)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact): View
    {
        return view('contacts.form', compact('contact'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreContactRequest $request, Contact $contact): RedirectResponse
    {
        $contact->update($request->validated());

        return to_route('contacts.index')->with('success', 'Kontak berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Contact $contact): RedirectResponse
    {
        $contact->delete();

        return to_route('contacts.index')->with('success', 'Kontak berhasil dihapus.');
    }
}
