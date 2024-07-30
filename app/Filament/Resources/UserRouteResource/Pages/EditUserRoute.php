<?php

namespace App\Filament\Resources\UserRouteResource\Pages;

use App\Filament\Resources\UserRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\EditRecord;

class EditUserRoute extends EditRecord
{
    protected static string $resource = UserRouteResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\ViewAction::make(),
            Actions\DeleteAction::make(),
        ];
    }
}
