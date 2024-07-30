<?php

namespace App\Filament\Resources\RewardResource\Pages;

use App\Filament\Resources\RewardResource;
use App\Models\Reward;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateReward extends CreateRecord
{
    protected static string $resource = RewardResource::class;

    protected function afterCreate(): void
    {
        /**
         * @var $record Reward
         */
        $record = $this->record;
        $record->user->increment('balance', $record->amount);
    }
}
