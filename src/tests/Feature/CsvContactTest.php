<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CsvContactTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function csvAttributes()
    {
        $header = 'first_name,email,phone';
        $row1 = 'Roanna,seke@mailinator.com ,seke@mailinator.com';
        $row2 = 'Louis,ryryziqi@mailinator.com ,+1 (362) 674-8282';
        $row3 = 'Gretchen,tuwucozyj@mailinator.com,+1 (202) 263-8005';
        $row4 = 'Jescie,focyr@mailinator.com,+1 (685) 814-7734';
        $row5 = 'Coby,tixevudi@mailinator.com,+1 (867) 475-1774';

        $content = implode("\n", [$header, $row1, $row2, $row3, $row4, $row5]);

        $file = UploadedFile::fake()->createWithContent('test.csv', $content);

        return ['file' => $file];
    }

    public function test_when_user_uploads_contact_csv_then_contacts_are_saved_in_database()
    {
        $this->withoutExceptionHandling();
        Storage::fake();

        $user = $this->signIn();
        $attributes = $this->csvAttributes();

        $this->get('uploaded-contacts')->assertOk();

        $this->post('uploaded-contacts', $attributes);

        $this->assertDatabaseHas('contacts', [
            'user_id' => $user->id,
            'first_name' => 'Roanna',
            'email' => 'seke@mailinator.com',
            'phone' => 'seke@mailinator.com',
        ]);
    }
}
