<?php

namespace App\Filament\Resources\UserRouteResource\Pages;

use App\Filament\Resources\UserRouteResource;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateUserRoute extends CreateRecord
{
    protected static string $resource = UserRouteResource::class;
}
