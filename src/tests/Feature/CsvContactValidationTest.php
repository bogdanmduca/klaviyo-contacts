<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CsvContactValidationTest extends TestCase
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

    protected function setUp(): void
    {
        parent::setUp();

        Storage::fake();
        $this->signIn();
        $this->endpoint = "uploaded-contacts";
        $this->attributes = $this->csvAttributes();
    }

    public function test_when_user_uploads_contact_csv_then_file_is_required()
    {
        $this->markTestSkipped('False positive');

        $this->attributes['file'] = '';

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['file']);
    }

    public function test_when_user_uploads_contact_csv_then_file_has_csv_extension()
    {
        $this->attributes['file'] = UploadedFile::fake()->create('Test.pdf');

        $this->post($this->endpoint, $this->attributes)
            ->assertSessionHasErrors(['file']);
    }
}
