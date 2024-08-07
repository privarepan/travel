<?php

namespace App\Filament\Resources;

use App\Filament\Resources\UserResource\Pages;
use App\Filament\Resources\UserResource\RelationManagers;
use App\Models\User;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletingScope;
use Filament\Tables\Actions\Action;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class UserResource extends Resource
{
    protected static ?string $model = User::class;
    protected static ?string $modelLabel = '用户管理';

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->label('名称')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('pid')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('level')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('role_lv')
                    ->label('角色等级')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('balance')
                    ->label('余额')
                    ->required()
                    ->numeric(),
                Forms\Components\TextInput::make('freeze')
                    ->label('冻结金额')
                    ->required()
                    ->numeric(),
                /*Forms\Components\Textarea::make('path')
                    ->columnSpanFull(),*/
                Forms\Components\TextInput::make('phone')->label('手机号')
                    ->tel()
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('invite_code')
                    ->label('邀请码')
                    ->required()
                    ->maxLength(255),
                Forms\Components\Toggle::make('status')
                    ->label('状态')
                    ->columnSpan(1/2)
                    ->required()
                    ->default(1),
                Forms\Components\Toggle::make('is_member')
                    ->label('是否加入会员')
                    ->columnSpan(1/2)
                    ->default(0),
                Forms\Components\Toggle::make('state')
                    ->label('是否实名认证')
                    ->columnSpanFull()
                    ->default(0),
                Forms\Components\TextInput::make('id_card')
                    ->label('身份证')
                    ->rules('required')
                    ->maxLength(18),
                Forms\Components\SpatieMediaLibraryFileUpload::make('img')->label('身份证照')
                    ->collection('authentication')
                    ->multiple()
                    ->columnSpanFull()
                    ->maxFiles(2),

            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')->label('ID'),
                Tables\Columns\TextColumn::make('name')->label('名称')
                    ->searchable(),
                Tables\Columns\TextColumn::make('id_card')->label('身份证')
                    ->searchable(),
                Tables\Columns\ToggleColumn::make('is_member')->label('是否会员')
                    ->sortable(),
                Tables\Columns\TextColumn::make('pid')
                    ->numeric(),
                Tables\Columns\TextColumn::make('level')
                    ->label('lv')
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('role_lv')->label('角色等级')
                    ->badge()
                    ->color(fn(Model $record,$state) => match ($state){
                        0 => '普通用户',
                        1 => '团队主管',
                        2 => '部门经理',
                        3 => '区域经理',
                        4 => '区域总裁',
                        5 => '合伙人',
                        default => '普通用户'
                    })
                    ->numeric()
                    ->sortable(),
                Tables\Columns\TextColumn::make('phone')->label('手机号')
                    ->searchable(),

                Tables\Columns\TextColumn::make('invite_code')
                    ->label('邀请码')
                    ->searchable()
                    ->toggleable(isToggledHiddenByDefault: true),

                Tables\Columns\TextColumn::make('status')->label('状态')
                    ->numeric()
                    ->sortable(),

                Tables\Columns\TextColumn::make('state')->label('是否实名')
                    ->state(fn(Model $record) => match ($record->state){
                        0 => '否',
                        1 => '是',
                    })
                    ->badge()
                    ->color(fn(Model $record,$state) => match ($state){
                        '否' => 'gray',
                        '是' => 'success',
                    })
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
            ->recordUrl(false)
            ->filters([
                static::filterLevel(),
                //
            ], layout: Tables\Enums\FiltersLayout::AboveContent)
            ->actions([
                static::createChildren(),
                static::authentication(),
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    static::createChildren(),
                ])->label('节点操作'),
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ])->label('操作'),
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
            'index' => Pages\ListUsers::route('/'),
            'create' => Pages\CreateUser::route('/create'),
            'view' => Pages\ViewUser::route('/{record}'),
            'edit' => Pages\EditUser::route('/{record}/edit'),
        ];
    }

    protected static function filterLevel()
    {
        return Tables\Filters\Filter::make('level')
            ->label('等级')
            ->form([
                Forms\Components\TextInput::make('level')
                    ->numeric()
                    ->minValue(0)
                    ->rules('nullable|numeric|min:0')
                    ->label('等级'),
            ])
            ->query(function (Builder $query, array $data) {
                if ($data['level']) {
                    $query->where('level', $data['level']);
                }
            });
    }

    protected static function createChildren()
    {
        return Action::make('添加子集')->form([
            Forms\Components\TextInput::make('number')
                ->label('请选择创建数量')
                ->numeric()
                ->minValue(1)
                ->rules('required|numeric|min:1')
                ->required()
        ])->action(function (array $data, User $record) {
            $num = $data['number'];
            $record->createChildren($num);
        })
            ->icon('heroicon-o-users')
            ->color('primary');
    }

    protected static function deleteChildren()
    {
        return Action::make('删除子集')->form([
            Forms\Components\TextInput::make('number')
                ->label('请选择移除数量')
                ->required()
        ])->action(function (array $data, User $record) {
            $num = $data['number'];
            $record->children()->take($num)->each(function (User $children) {
                $children->deleteChildren();
            });
        });
    }

    protected static function authentication()
    {
        return Action::make('认证审批')
            ->fillForm(function (User $record) {
                return $record->toArray();
            })
            ->form([
                Forms\Components\TextInput::make('name')->disabled(),
                Forms\Components\TextInput::make('id_card')->label('身份证')
                    ->disabled(),
                Forms\Components\SpatieMediaLibraryFileUpload::make('img')
                    ->label('身份证照')
                    ->collection('authentication')
                    ->multiple()
                    ->disabled(),

                Forms\Components\Radio::make('state')
                    ->label('认证状态')
                    ->options([
                        0 => '退回',
                        1 => '审批通过',
                    ])->required(),
            ])->action(function (array $data, User $record) {
                $record->state = $data['state'];
                $record->save();
                Notification::make()
                    ->title('操作成功')
                    ->success()
                    ->send();
            });
    }

}
