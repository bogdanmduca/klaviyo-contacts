<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class UploadedContactController extends Controller
{
    public function create()
    {
        return view('uploaded-contacts.create');
    }

    public function store(Request $request)
    {
        $attributes = $request->validate([
            'file' => ['required', 'file', 'mimes:csv']
        ]);

        $path = $attributes['file']->store('uploads');
        $contacts = $this->parseCSV($path);
        $contactsDb = [];

        try {
            DB::beginTransaction();

            foreach ($contacts as $contact) {
                $contactsDb[] = Contact::create([
                    'user_id' => Auth::user()->id,
                    'first_name' => $contact[0],
                    'email' => $contact[1],
                    'phone' => $contact[2],
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
        }

        Storage::delete($path);

        return redirect()->to('contacts');
    }

    private function parseCSV($path)
    {
        $filePath = Storage::path($path);
        $file = fopen($filePath, 'r');
        $data = array();
        $position = 0;
        while (($content = fgetcsv($file, 1000, ",")) !== FALSE) {
            if ($position === 0) {
                $position++;
                continue;
            }

            $length = count($content);
            for ($column = 0; $column < $length; $column++) {
                $data[$position][] = $content[$column];
            }

            $position++;
        }

        fclose($file);

        return $data;
    }
}
