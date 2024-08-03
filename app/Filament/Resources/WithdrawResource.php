<?php

namespace App\Filament\Resources;

use App\Filament\Resources\WithdrawResource\Pages;
use App\Filament\Resources\WithdrawResource\RelationManagers;
use App\Models\Withdraw;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class WithdrawResource extends Resource
{
    protected static ?string $model = Withdraw::class;
    protected static ?string $modelLabel = '提现记录';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user','phone')
                    ->required()
                    ->label('用户'),
                Forms\Components\TextInput::make('amount')
                    ->label('提现金额')
                    ->required(),
                Forms\Components\Select::make('pay_type')
                    ->label('提现方式')
                    ->options([
                        0 => '支付宝',
                        1 => '银行卡'
                    ])
                    ->live()
                    ->required(),
                Forms\Components\TextInput::make('account')
                    ->required()
                    ->label('卡号')
                    ->helperText('提现方式是支付宝就是支付宝账号')
                    ->maxLength(255),
                Forms\Components\TextInput::make('name')
                    ->label('真实姓名')
                    ->helperText('提现对应的真实姓名')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('bank_name')
                    ->label('开户行')
                    ->hidden(fn(Forms\Get $get) => !$get('pay_type'))
                    ->maxLength(30),
                Forms\Components\Select::make('status')
                    ->options([
                        '待审批',
                        '审批通过',
                        '驳回',
                    ])
                    ->label('提现状态')
                    ->columnSpanFull()
                    ->required()
                    ->default(0),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('用户')
                    ->sortable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('提现金额')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\SelectColumn::make('status')
                    ->label('提现状态')
                    ->options([
                        '待审批',
                        '审批通过',
                        '驳回',
                    ])
                    ->extraAttributes([
                        '@save.window' => '$wire.$refresh'
                    ])
                    ->afterStateUpdated(function (Withdraw $record, $state,Tables\Columns\SelectColumn $component){
                        if ((int)$state === 2) {
                            $record->user->freezeRelesea($record->amount);
                        }
                        $component->getLivewire()->dispatch('save');
                    })
                    ->disabled(fn($record) => $record->status !== 0)
                    ->sortable(),
                Tables\Columns\TextColumn::make('pay_type')
                    ->label('提现方式')
                    ->state(fn(Model $record) => match ($record->pay_type){
                        0 => '支付宝',
                        1 => '银行卡',
                    })
                    ->badge()
                    ->color(fn(Model $record,$state) => match ($state){
                        '支付宝' => 'info',
                        '银行卡' => 'primary',
                    }),
                Tables\Columns\TextColumn::make('account')
                    ->label('卡号')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name')
                    ->label('真实姓名')
                    ->searchable(),
                Tables\Columns\TextColumn::make('bank_name')
                    ->label('开户行')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
//                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListWithdraws::route('/'),
            'create' => Pages\CreateWithdraw::route('/create'),
            'view' => Pages\ViewWithdraw::route('/{record}'),
            'edit' => Pages\EditWithdraw::route('/{record}/edit'),
        ];
    }
}
