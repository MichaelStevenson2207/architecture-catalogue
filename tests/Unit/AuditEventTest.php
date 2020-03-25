<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\Notification;

use Tests\TestCase;

use App\Services\Audit as AuditClient;

use App\User;
use App\Entry;

class AuditEventTest extends TestCase
{
    /**
     * Authorisation token creation.
     *
     * @return void
     */
    public function testGetAuthorisationToken()
    {
        $auditClient = new AuditClient();
        $token = $auditClient->getAuthorisationToken();
        $this->assertTrue(is_string($token));
    }

    /**
     * Audit an event.
     *
     * @return void
     */
    public function testCanAuditEntryCreation()
    {
        // stops notification being physically sent when a user is created
        Notification::fake();

        $user = $this->loginAsFakeUser(true);
        // create a catalogue entry
        $entry = factory(Entry::class)->create();
        // visual check now but replace with a search using the API
        $this->assertTrue(true);
    }

    /**
     * Audit an event.
     *
     * @return void
     */
    public function testCanFetchAnAuditEvent()
    {
        // create an audit entry
        $id = 1;

        // now check that it exists
        $auditClient = new AuditClient();
        $event = $auditClient->getEvent($id);
        $this->assertTrue($id == $event->id);
    }
}
