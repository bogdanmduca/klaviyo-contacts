<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;
use Tests\TestCase;

class UserCreatesContactValidationTest extends TestCase
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

        $this->signIn();
        $this->endpoint = "contacts";
        $this->attributes = $this->contactAttributes();
    }

    public function test_when_user_creates_contact_then_first_name_is_required()
    {
        $this->attributes['first_name'] = '';

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['first_name']);
    }

    public function test_when_user_creates_contact_then_first_name_is_255_characters_long()
    {
        $this->attributes['first_name'] = Str::random(256);

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['first_name']);
    }

    public function test_when_user_creates_contact_then_phone_is_required()
    {
        $this->attributes['phone'] = '';

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['phone']);
    }

    public function test_when_user_creates_contact_then_phone_is_255_characters_long()
    {
        $this->attributes['phone'] = Str::random(256);

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['phone']);
    }

    public function test_when_user_creates_contact_then_email_is_required()
    {
        $this->attributes['email'] = '';

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['email']);
    }

    public function test_when_user_creates_contact_then_email_is_255_characters_long()
    {
        $this->attributes['email'] = Str::random(256);

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['email']);
    }

    public function test_when_user_creates_contact_then_email_is_valid()
    {
        $this->attributes['email'] = $this->faker()->word();

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['email']);
    }
}
