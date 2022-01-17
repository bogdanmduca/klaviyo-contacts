<?php

namespace App\Listeners;

use App\Events\ContactCreated;
use App\Services\ListService;

class CreateRemoteContact
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
     * @param  \App\Events\ContactCreated  $event
     * @return void
     */
    public function handle(ContactCreated $event)
    {
        $listService = new ListService();

        $remoteContacts = $listService->addProfile($event->contact->user->remote_list_id, [$event->contact->attributesToArray()]);

        foreach ($remoteContacts as $contact) {
            $event->contact->update(['remote_id' => $contact->id]);
        }
    }
}
