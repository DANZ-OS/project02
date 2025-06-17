<?php

namespace App\Filament\Resources\RiwayatKegiatanResource\Pages;

use App\Filament\Resources\RiwayatKegiatanResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListRiwayatKegiatans extends ListRecords
{
    protected static string $resource = RiwayatKegiatanResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
