<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

use App\Entry;
use App\Traits\AuditsActivity;
use App\Exceptions\AuditException;

use Illuminate\Support\Facades\Log;

class EntryBasicTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Entry creation and audit.
     *
     * @return void
     */
    public function testCatalogueEntryCreated()
    {
        // create a catalogue entry
        $entry = factory(Entry::class)->make();
        $entry->save();
        $this->assertDatabaseHas('entries', [
            'id' => $entry->id
        ]);
        // should check external audit log
    }

    /**
     * Entry update and audit.
     *
     * @return void
     */
    public function testCatalogueEntryUpdated()
    {
        // create an entry
        $entry = factory(Entry::class)->create();
        // now update it
        $entry->version = "99";
        $entry->save();
        $this->assertDatabaseHas('entries', [
            'version' => "99"
        ]);
        // should check external audit log
    }

    /**
     * Entry delete and audit.
     *
     * @return void
     */
    public function testCatalogueEntryDeleted()
    {
        // create an entry
        $entry = factory(Entry::class)->create();
        // now delete it
        $entry->delete();
        $this->assertDatabaseMissing('entries', [
            'id' => $entry->id
        ]);
        // should check external audit log
    }
}
