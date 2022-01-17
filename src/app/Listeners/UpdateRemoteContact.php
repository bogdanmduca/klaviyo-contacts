<?php

namespace App\Listeners;

use App\Events\ContactUpdated;
use App\Services\PersonService;

class UpdateRemoteContact
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  \App\Events\ContactUpdated  $event
     * @return void
     */
    public function handle(ContactUpdated $event)
    {
        $personService = new PersonService();

        $personService->update($event->contact->remote_id, $event->contact->attributesToArray());
    }
}
