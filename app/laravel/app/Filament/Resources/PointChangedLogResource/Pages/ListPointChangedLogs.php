<?php

namespace App\Filament\Resources\PointChangedLogResource\Pages;

use App\Filament\Resources\PointChangedLogResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointChangedLogs extends ListRecords
{
    protected static string $resource = PointChangedLogResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
