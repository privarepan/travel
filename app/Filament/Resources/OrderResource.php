<?php

namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Filament\Resources\OrderResource\RelationManagers;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Illuminate\Support\Str;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;
    protected static ?string $modelLabel = '订单管理';
    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->label('用户')
                    ->relationship('user','name')
                    ->searchable(['name','id_card','phone','id'])
                    ->preload(),
                Forms\Components\TextInput::make('order_no')
                    ->label('订单号')
                    ->default(Str::orderedUuid()->toString())
                    ->maxLength(255),
                Forms\Components\TextInput::make('amount')
                    ->label('金额')
                    ->default(2999)
                    ->numeric(),
                Forms\Components\Toggle::make('status')
                    ->label('状态')
                    ->columnSpanFull()
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
                Tables\Columns\TextColumn::make('order_no')
                    ->label('订单号')
                    ->searchable(),
                Tables\Columns\TextColumn::make('amount')
                    ->label('金额')
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status')
                    ->label('状态')
                    ->state(fn(Model $record) => match ($record->status){
                        0 => '待支付',
                        1 => '支付成功',
                    })
                    ->badge()
                    ->color(fn(Model $record,$state) => match ($state){
                        '待支付' => 'gray',
                        '支付成功' => 'success',
                    })
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
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
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'view' => Pages\ViewOrder::route('/{record}'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
