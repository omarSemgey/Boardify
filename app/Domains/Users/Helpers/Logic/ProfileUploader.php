<?php

namespace App\Domains\Users\Helpers\Logic;

use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;

class ProfileUploader
{
    public static function upload(UploadedFile $file): string
    {
        $profile = $file;
        $extension = $profile->getClientOriginalExtension();
        $filename = Str::random(32) . '.' . $extension;
        $path = $profile->storeAs('userProfiles', $filename, 'public');
        return $path;
    }
}
