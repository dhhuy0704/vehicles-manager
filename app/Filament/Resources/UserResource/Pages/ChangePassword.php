<?php

namespace App\Filament\Resources\UserResource\Pages;

use App\Filament\Resources\UserResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class ChangePassword extends EditRecord
{
    protected static string $resource = UserResource::class;
}
