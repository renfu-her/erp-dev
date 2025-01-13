<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EmployeeResource\Pages;
use App\Models\Employee;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EmployeeResource extends Resource
{
    protected static ?string $model = Employee::class;
    protected static ?string $navigationGroup = '人事管理';
    protected static ?string $navigationIcon = 'heroicon-o-users';
    protected static ?string $navigationLabel = '員工管理';
    protected static ?string $modelLabel = '員工';
    protected static ?string $pluralModelLabel = '員工';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Card::make()
                    ->schema([
                        Forms\Components\TextInput::make('employee_id')
                            ->label('員工編號')
                            ->required()
                            ->unique(ignoreRecord: true)
                            ->maxLength(255),

                        Forms\Components\Select::make('user_id')
                            ->relationship('user', 'name')
                            ->label('系統用戶')
                            ->required(),

                        Forms\Components\Select::make('department_id')
                            ->relationship('department', 'name')
                            ->label('所屬部門')
                            ->required(),

                        Forms\Components\Select::make('position_id')
                            ->relationship('position', 'name')
                            ->label('職位')
                            ->required(),

                        Forms\Components\TextInput::make('name')
                            ->label('姓名')
                            ->required()
                            ->maxLength(255),

                        Forms\Components\DatePicker::make('birth_date')
                            ->label('出生日期')
                            ->required(),

                        Forms\Components\DatePicker::make('hire_date')
                            ->label('到職日期')
                            ->required(),

                        Forms\Components\TextInput::make('phone')
                            ->label('聯絡電話')
                            ->tel()
                            ->required()
                            ->maxLength(255),

                        Forms\Components\Textarea::make('address')
                            ->label('聯絡地址')
                            ->maxLength(255),

                        Forms\Components\Select::make('status')
                            ->label('狀態')
                            ->options([
                                'active' => '在職',
                                'inactive' => '離職',
                            ])
                            ->required()
                            ->default('active'),
                    ])
                    ->columns(2),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('employee_id')
                    ->label('員工編號')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('name')
                    ->label('姓名')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('department.name')
                    ->label('部門')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('position.name')
                    ->label('職位')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\SelectColumn::make('status')
                    ->label('狀態')
                    ->options([
                        'active' => '在職',
                        'inactive' => '離職',
                    ])
                    ->sortable(),

                Tables\Columns\TextColumn::make('hire_date')
                    ->label('到職日期')
                    ->date()
                    ->sortable(),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('department')
                    ->relationship('department', 'name')
                    ->label('部門'),

                Tables\Filters\SelectFilter::make('position')
                    ->relationship('position', 'name')
                    ->label('職位'),

                Tables\Filters\SelectFilter::make('status')
                    ->options([
                        'active' => '在職',
                        'inactive' => '離職',
                    ])
                    ->label('狀態'),

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
            'index' => Pages\ListEmployees::route('/'),
            'create' => Pages\CreateEmployee::route('/create'),
            'edit' => Pages\EditEmployee::route('/{record}/edit'),
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
