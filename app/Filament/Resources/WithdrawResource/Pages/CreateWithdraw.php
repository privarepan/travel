<?php

namespace App\Filament\Resources\WithdrawResource\Pages;

use App\Filament\Resources\WithdrawResource;
use App\Models\Withdraw;
use Filament\Actions;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdraw extends CreateRecord
{
    protected static string $resource = WithdrawResource::class;

    protected function afterCreate(): void
    {
        /**
         * @var $record Withdraw
         */
        $record = $this->record;
        if ($record->status !== 2) {
            $record->user->increment('freeze', $record->amount);;
        }
    }
}
