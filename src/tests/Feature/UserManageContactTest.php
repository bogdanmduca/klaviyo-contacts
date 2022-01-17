<?php

namespace Tests\Feature;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class UserManageContactTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function signIn($user = null)
    {
        $user = $user ?? User::factory()->create();

        $this->actingAs($user);

        return $user;
    }

    protected function contactAttributes()
    {
        return  [
            'first_name' => $this->faker()->firstName(),
            'email' => $this->faker()->email(),
            'phone' => $this->faker()->phoneNumber(),
        ];
    }

    public function test_when_user_creates_contact_then_is_saved_in_database()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $this->get('contacts/create')->assertOk();

        $attributes = $this->contactAttributes();

        $this->post('contacts', $attributes);

        $attributes['user_id'] = $user->id;
        $this->assertDatabaseHas('contacts', $attributes);
    }

    public function test_after_user_creates_contact_then_is_redirected_to_contact_list()
    {
        $this->withoutExceptionHandling();

        $this->signIn();

        $attributes = $this->contactAttributes();

        $this->post('contacts', $attributes)->assertRedirect('contacts');
    }

    public function test_when_user_view_contact_list_then_his_contacts_are_retrieved_from_database()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $contacts = Contact::factory()->for($user)->count(5)->create();

        $response = $this->get('contacts');

        foreach ($contacts as $contact) {
            $response->assertSee($contact->first_name);
        }
    }

    public function test_when_user_updates_contact_then_database_is_updated()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();
        $contact = Contact::factory()->for($user)->create();
        $attributes = $this->contactAttributes();

        $this->put("contacts/{$contact->id}", $attributes);

        $attributes['user_id'] = $user->id;
        $this->assertDatabaseHas('contacts', $attributes);
    }

    public function test_after_user_updates_contact_then_is_redirected_to_contact_list()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();
        $contact = Contact::factory()->for($user)->create();
        $attributes = $this->contactAttributes();

        $this->put("contacts/{$contact->id}", $attributes)
            ->assertRedirect(route('contacts.index'));
    }

    public function test_when_user_edits_contact_then_current_contact_is_returned()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();

        $contact = Contact::factory()->for($user)->create();

        $this->get("contacts/{$contact->id}/edit")
            ->assertSee($contact->first_name)
            ->assertSee($contact->phone)
            ->assertSee($contact->email);
    }

    public function test_when_user_removes_a_contact_then_database_is_updated()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();
        $contact = Contact::factory()->for($user)->create();

        $this->assertDatabaseHas('contacts', $contact->attributesToArray());

        $this->delete("contacts/{$contact->id}");

        $this->assertDatabaseMissing('contacts', $contact->attributesToArray());
    }

    public function test_after_user_removes_a_contact_then_is_redirected_to_contact_list()
    {
        $this->withoutExceptionHandling();

        $user = $this->signIn();
        $contact = Contact::factory()->for($user)->create();

        $this->delete("contacts/{$contact->id}")->assertRedirect(route('contacts.index'));
    }
}
