<?php

namespace App\Console\Commands\Traits\Helpers;

use Illuminate\Support\Str;

trait ValidatesUsersInputTrait
{
    protected function validateInput(string $value): string
    {
        return Str::studly(strtolower(trim($value)));
    }
}