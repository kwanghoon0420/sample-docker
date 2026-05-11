<?php

namespace App\Filament\Resources\PointDetailChangedLogResource\Pages;

use App\Filament\Resources\PointDetailChangedLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointDetailChangedLogs extends ListRecords
{
    protected static string $resource = PointDetailChangedLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
