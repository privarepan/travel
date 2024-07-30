<?php

namespace App\Filament\Resources\UserRouteResource\Pages;

use App\Filament\Resources\UserRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewUserRoute extends ViewRecord
{
    protected static string $resource = UserRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
