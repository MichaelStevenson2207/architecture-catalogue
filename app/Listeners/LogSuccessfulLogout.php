<?php

namespace App\Listeners;

use Illuminate\Auth\Events\Logout;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

use App\Services\Audit as AuditLogger;

class LogSuccessfulLogout implements ShouldQueue
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
     * @param  Logout  $event
     * @return void
     */
    public function handle(Logout $event)
    {
        $logger = new AuditLogger();

        $payload = ["auth" => [
            "event" => "logout",
            "id" => $event->user->id
        ]];

        $logger->recordEvent(
            'logout',
            0,
            'Auth',
            $event->user->id,
            get_class($event->user),
            json_encode($payload)
        );
    }
}
