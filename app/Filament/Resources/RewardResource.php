<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RewardResource\Pages;
use App\Filament\Resources\RewardResource\RelationManagers;
use App\Models\Reward;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RewardResource extends Resource
{
    protected static ?string $model = Reward::class;
    protected static ?string $modelLabel = '奖励列表';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user','name')
                    ->label('用户名称'),
                Forms\Components\Select::make('original_id')
                    ->relationship('original','name')
                    ->label('来源用户名称'),
                Forms\Components\TextInput::make('phone')
                    ->label('手机号')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('original_phone')
                    ->label('来源手机号')
                    ->tel()
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->label('奖励金额'),
                Forms\Components\TextInput::make('role_rate')
                    ->label('角色分成比例')
                    ->numeric(),
                Forms\Components\TextInput::make('role_lv')
                    ->label('角色等级')
                    ->numeric(),
                Forms\Components\TextInput::make('rate')
                    ->label('实际分成比例')
                    ->numeric(),
                Forms\Components\TextInput::make('remark')
                    ->label('备注')
                    ->maxLength(255),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('user.name')
                    ->label('用户名称')
                    ->sortable(),
                Tables\Columns\TextColumn::make('original.name')
                    ->label('来源用户名称')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')
                    ->label('手机号')
                    ->searchable(),
                Tables\Columns\TextColumn::make('original_phone')
                    ->label('来源手机号')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('奖励金额')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role_rate')
                    ->label('角色分成比例')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('role_lv')
                    ->label('角色等级')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('rate')
                    ->label('实际分成比例')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('remark')
                    ->label('备注')
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->defaultSort('id','desc')
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
            'index' => Pages\ListRewards::route('/'),
            'create' => Pages\CreateReward::route('/create'),
            'view' => Pages\ViewReward::route('/{record}'),
//            'edit' => Pages\EditReward::route('/{record}/edit'),
        ];
    }
}
