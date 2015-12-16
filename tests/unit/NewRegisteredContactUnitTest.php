<?php

use App\Events\NewContactWasRegistered;
use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class NewContactWasRegisteredUnitTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * @covers App\Events\NewContactWasRegistered::__construct
     * @covers App\Handlers\Events\LinkContactToExistingUser::handle
     * @test
     */
    public function it_fires_event_and_links_user()
    {
        $user = $this->createUser(['email' => 'guest@example.org', 'password' => bcrypt('demoguest')]);

        $contact = $this->createContact(['email' => 'guest@example.org']);

        event(new NewContactWasRegistered($contact));

        $this->seeInDatabase('contacts', ['email' => $user->email, 'user_id' => $user->id]);
    }

    /**
     * @covers App\Events\NewContactWasRegistered::broadcastOn
     * @test
     */
    public function it_verifies_broadcasts_on()
    {
        $contact = $this->createContact(['email' => 'guest@example.org']);

        $event = new NewContactWasRegistered($contact);

        $this->assertEquals([], $event->broadcastOn());
    }

    /////////////
    // HELPERS //
    /////////////

    private function createUser($overrides = [])
    {
        $user = factory(User::class)->create($overrides);

        return $user;
    }

    private function createContact($overrides = [])
    {
        $contact = factory(Contact::class)->create($overrides);

        return $contact;
    }
}
