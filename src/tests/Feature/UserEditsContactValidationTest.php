<?php

namespace Tests\Feature;

use App\Models\Contact;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserEditsContactValidationTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    private $endpoint;

    private $attributes;

    protected function contactAttributes()
    {
        return  [
            'first_name' => $this->faker()->firstName(),
            'email' => $this->faker()->email(),
            'phone' => $this->faker()->phoneNumber(),
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $contact = Contact::factory()->create();
        $this->signIn($contact->user);
        $this->endpoint = "contacts/{$contact->id}";
        $this->attributes = $this->contactAttributes();
    }

    public function test_when_user_edits_contact_then_first_name_is_required()
    {
        $this->attributes['first_name'] = '';

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['first_name']);
    }

    public function test_when_user_edits_contact_then_first_name_is_255_characters_long()
    {
        $this->attributes['first_name'] = Str::random(256);

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['first_name']);
    }

    public function test_when_user_edits_contact_then_phone_is_required()
    {
        $this->attributes['phone'] = '';

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['phone']);
    }

    public function test_when_user_edits_contact_then_phone_is_255_characters_long()
    {
        $this->attributes['phone'] = Str::random(256);

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['phone']);
    }

    public function test_when_user_edits_contact_then_email_is_required()
    {
        $this->attributes['email'] = '';

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['email']);
    }

    public function test_when_user_edits_contact_then_email_is_255_characters_long()
    {
        $this->attributes['email'] = Str::random(256);

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['email']);
    }

    public function test_when_user_edits_contact_then_email_is_valid()
    {
        $this->attributes['email'] = $this->faker()->word();

        $this->put($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['email']);
    }
}
