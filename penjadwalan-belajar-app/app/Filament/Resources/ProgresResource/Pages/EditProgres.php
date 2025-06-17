<?php

namespace App\Filament\Resources\ProgresResource\Pages;

use App\Filament\Resources\ProgresResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditProgres extends EditRecord
{
    protected static string $resource = ProgresResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
        ];
    }
}
