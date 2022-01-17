<?php

namespace App\Http\Controllers;

use App\Services\TrackEventService;
use Illuminate\Support\Facades\Auth;

class TrackerController extends Controller
{
    public function store()
    {
        $trackerService = new TrackEventService();

        $trackerService->store(
            "Clicked button test",
            [
                '$email' => Auth::user()->email
            ]
        );

        return redirect()->to('contacts');
    }
}
