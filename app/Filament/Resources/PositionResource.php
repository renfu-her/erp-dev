<?php

namespace App\Filament\Resources;

use App\Filament\Resources\PositionResource\Pages;
use App\Filament\Resources\PositionResource\RelationManagers;
use App\Models\Position;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class PositionResource extends Resource
{
    protected static ?string $model = Position::class;
    protected static ?string $navigationGroup = '人事管理';
    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = '職位管理';
    protected static ?string $modelLabel = '職位';
    protected static ?string $pluralModelLabel = '職位';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->label('職位名稱')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Select::make('level')
                            ->label('職級')
                            ->options([
                                'entry' => '初級',
                                'intermediate' => '中級',
                                'senior' => '高級',
                                'lead' => '主管',
                                'manager' => '經理',
                                'director' => '總監',
                            ])
                            ->required(),

                        Forms\Components\Textarea::make('description')
                            ->label('職位描述')
                            ->rows(3)
                            ->maxLength(65535),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('職位名稱')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\SelectColumn::make('level')
                    ->label('職級')
                    ->options([
                        'entry' => '初級',
                        'intermediate' => '中級',
                        'senior' => '高級',
                        'lead' => '主管',
                        'manager' => '經理',
                        'director' => '總監',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('employees_count')
                    ->label('員工數量')
                    ->counts('employees')
                    ->sortable(),

                Tables\Columns\TextColumn::make('created_at')
                    ->label('建立時間')
                    ->dateTime()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('level')
                    ->label('職級')
                    ->options([
                        'entry' => '初級',
                        'intermediate' => '中級',
                        'senior' => '高級',
                        'lead' => '主管',
                        'manager' => '經理',
                        'director' => '總監',
                    ]),

                Tables\Filters\TrashedFilter::make(),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
                Tables\Actions\RestoreAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                    Tables\Actions\RestoreBulkAction::make(),
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
            'index' => Pages\ListPositions::route('/'),
            'create' => Pages\CreatePosition::route('/create'),
            'edit' => Pages\EditPosition::route('/{record}/edit'),
        ];
    }

    public static function getNavigationBadge(): ?string
    {
        return static::getModel()::count();
    }

    public static function getEmptyStateHeading(): string
    {
        return '無' . static::$modelLabel;
    }

    public static function getCreateButtonLabel(): string
    {
        return '新增' . static::$modelLabel;
    }
}
