<?php

namespace App\Http\Controllers;

use App\Http\Requests\ContactRequest;
use App\Models\Contact;
use App\Services\ListService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    protected $apiKey = 'pk_2ddb505c83f408df036fee25a05438a0ff';

    public function index()
    {
        $contacts = Auth::user()->latestContactsPaginated(10);

        return view('contacts.index', compact('contacts'));
    }

    public function create()
    {
        return view('contacts.create');
    }

    public function store(ContactRequest $request)
    {
        $attributes = $request->validated();

        try {
            DB::beginTransaction();

            Contact::create([
                'user_id' => Auth::user()->id,
                'first_name' => $attributes['first_name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'],
            ]);
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }


        return redirect()->to('contacts');
    }

    public function edit(Contact $contact)
    {
        return view('contacts.edit', compact('contact'));
    }

    public function update(Contact $contact, ContactRequest $request)
    {
        $attributes = $request->validated();

        try {
            DB::beginTransaction();

            $contact->update([
                'first_name' => $attributes['first_name'],
                'email' => $attributes['email'],
                'phone' => $attributes['phone'],
            ]);

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->to('contacts');
    }

    public function destroy(Contact $contact)
    {
        try {
            DB::beginTransaction();

            $listService = new ListService();
            $listService->removeProfile(Auth::user()->remote_list_id, $contact->email);

            $contact->delete();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        return redirect()->to('contacts');
    }
}
