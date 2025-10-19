<?php

namespace App\Filament\Resources;

use App\Filament\Resources\RefuelRecordResource\Pages;
use App\Filament\Resources\RefuelRecordResource\RelationManagers;
use App\Models\RefuelRecord;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class RefuelRecordResource extends Resource
{
    protected static ?string $model = RefuelRecord::class;

    protected static ?string $navigationIcon = 'heroicon-o-beaker';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\DatePicker::make('date')
                    ->required()
                    ->default(now()),

                Forms\Components\TimePicker::make('time')
                    ->required()
                    ->default(now())
                    ->seconds(false),

                Forms\Components\TextInput::make('odometer')
                    ->required()
                    ->numeric()
                    ->suffix('km')
                    ->step(0.01),

                Forms\Components\TextInput::make('unit_price')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01),

                Forms\Components\TextInput::make('litres')
                    ->required()
                    ->numeric()
                    ->suffix('L')
                    ->step(0.01),

                Forms\Components\TextInput::make('total_cost')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->step(0.01),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('date')
                    ->date()
                    ->sortable(),

                Tables\Columns\TextColumn::make('time')
                    ->time()
                    ->sortable(),

                Tables\Columns\TextColumn::make('odometer')
                    ->numeric(2)
                    ->suffix('km')
                    ->sortable(),

                Tables\Columns\TextColumn::make('unit_price')
                    ->money('USD')
                    ->sortable(),

                Tables\Columns\TextColumn::make('litres')
                    ->numeric(2)
                    ->suffix('L')
                    ->sortable(),

                Tables\Columns\TextColumn::make('total_cost')
                    ->money('USD')
                    ->sortable(),
            ])
            ->defaultSort('date', 'desc')
            ->filters([
                //
            ])
            ->actions([
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
            'index' => Pages\ListRefuelRecords::route('/'),
            'create' => Pages\CreateRefuelRecord::route('/create'),
            'edit' => Pages\EditRefuelRecord::route('/{record}/edit'),
        ];
    }
}
