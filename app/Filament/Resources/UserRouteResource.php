<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserRouteResource\Pages;
use App\Filament\Resources\UserRouteResource\RelationManagers;
use App\Models\User;
use App\Models\UserRoute;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Livewire\Attributes\Url;


class UserRouteResource extends Resource
{
    protected static ?string $model = UserRoute::class;
    protected static ?string $modelLabel = '预约路线';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('user_id')
                    ->relationship('user','name')
                    ->label('预约用户')
                    ->searchable(['name','phone','id_card'])
                    ->preload()
                    ->helperText('可以使用名称、手机号、身份证号查询')
                    ->afterStateUpdated(function (Forms\Set $set,$state){
                        $user = User::find($state);
                        $set('name_first',$user->name);
                        $set('id_card_first',$user->id_card);
                        $set('mobile',$user->phone);
                    })
                    ->live()
                    ->required(),
                Forms\Components\Select::make('route_id')
                    ->label('路线')
                    ->relationship(name: 'route', titleAttribute: 'title')
                    ->required(),
                Forms\Components\DatePicker::make('start_at')
                    ->required()
                    ->label('预约日期'),
                Forms\Components\TextInput::make('mobile')
                    ->label('预约手机号')
                    ->required()
                    ->maxLength(20),
                Forms\Components\TextInput::make('name_first')
                    ->label('姓名1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('name_second')
                    ->label('姓名2')
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_card_first')
                    ->label('身份证1')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('id_card_second')
                    ->label('身份证2')
                    ->maxLength(255),
                Forms\Components\Textarea::make('remark')
                    ->label('备注')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('route.title')
                    ->label('预约路线')
                    ->searchable()
                    ->url(fn (UserRoute $record): string => route('filament.admin.resources.routes.view', ['record' => $record->route_id]))
                    ->sortable(),
                Tables\Columns\TextColumn::make('start_at')
                    ->label('预约日期')
                    ->date()
                    ->sortable(),
                Tables\Columns\TextColumn::make('status_label')->label('状态')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        '预约成功' => 'success',
                        '行程结束' => 'gray'
                    }),
                Tables\Columns\TextColumn::make('mobile')
                    ->label('预约手机号')
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_first')
                    ->label('姓名1')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('name_second')
                    ->label('姓名2')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_card_first')
                    ->label('身份证1')
                    ->copyable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_card_second')
                    ->label('身份证2')
                    ->copyable()
                    ->toggleable(isToggledHiddenByDefault: true)
                    ->searchable(),
                Tables\Columns\TextColumn::make('created_at')->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('deleted_at')->translateLabel()
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
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
            'index' => Pages\ListUserRoutes::route('/'),
            'create' => Pages\CreateUserRoute::route('/create'),
            'view' => Pages\ViewUserRoute::route('/{record}'),
            'edit' => Pages\EditUserRoute::route('/{record}/edit'),
        ];
    }
}
