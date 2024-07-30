<?php

namespace App\Filament\Resources\UserRouteResource\Pages;

use App\Filament\Resources\UserRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ListRecords;

class ListUserRoutes extends ListRecords
{
    protected static string $resource = UserRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\CreateAction::make(),
        ];
    }
}
