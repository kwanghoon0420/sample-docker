<?php

namespace App\Filament\Resources\PointDetailResource\Pages;

use App\Filament\Resources\PointDetailResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListPointDetails extends ListRecords
{
    protected static string $resource = PointDetailResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
