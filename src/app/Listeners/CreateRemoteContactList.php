<?php

namespace App\Listeners;

use App\Events\UserCreated;
use App\Services\CreateListAction;

class CreateRemoteContactList
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
     * @param  \App\Events\UserCreated  $event
     * @return void
     */
    public function handle(UserCreated $event)
    {
        $listService = new CreateListAction();

        $remoteList = $listService->execute($event->user->email);

        $event->user->update(['remote_list_id' => $remoteList->list_id]);
    }
}
