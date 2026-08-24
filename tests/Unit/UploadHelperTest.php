<?php

namespace Tests\Unit;

use App\Helpers\UploadHelper;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\File;
use Tests\TestCase;

class UploadHelperTest extends TestCase
{
    public function test_url_resolves_default_profile_fallback_for_empty_filename(): void
    {
        $urlMale = UploadHelper::url('profile', '', 'Male');
        $urlFemale = UploadHelper::url('profile', null, 'Female');

        $this->assertStringContainsString('uploads/profile/default_male.jpg', $urlMale);
        $this->assertStringContainsString('uploads/profile/default_female.jpg', $urlFemale);
    }

    public function test_url_resolves_logo_fallback_for_empty_filename(): void
    {
        $url = UploadHelper::url('logo', '');
        $this->assertStringContainsString('uploads/logo/logo_1538139736.jpg', $url);
    }

    public function test_url_returns_full_external_urls_as_is(): void
    {
        $externalUrl = 'https://example.com/avatar.jpg';
        $url = UploadHelper::url('profile', $externalUrl);
        $this->assertEquals($externalUrl, $url);
    }

    public function test_file_upload_and_deletion(): void
    {
        $fakeFile = UploadedFile::fake()->image('test_avatar.jpg');
        $savedFilename = UploadHelper::upload($fakeFile, 'profile', 'test_profile');

        $this->assertNotEmpty($savedFilename);
        $this->assertStringStartsWith('test_profile_', $savedFilename);

        $resolvedUrl = UploadHelper::url('profile', $savedFilename);
        $this->assertStringContainsString($savedFilename, $resolvedUrl);

        $deleted = UploadHelper::delete('profile', $savedFilename);
        $this->assertTrue($deleted);
    }
}
