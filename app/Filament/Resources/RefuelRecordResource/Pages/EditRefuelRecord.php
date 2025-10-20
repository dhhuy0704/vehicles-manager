<?php

namespace App\Filament\Resources\RefuelRecordResource\Pages;

use App\Filament\Resources\RefuelRecordResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditRefuelRecord extends EditRecord
{
    protected static string $resource = RefuelRecordResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
