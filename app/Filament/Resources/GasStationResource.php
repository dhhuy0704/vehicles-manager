<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GasStationResource\Pages;
use App\Models\GasStation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class GasStationResource extends Resource
{
    protected static ?string $model = GasStation::class;

    protected static ?string $navigationGroup = 'Vehicle Management';

    protected static ?string $navigationIcon = 'heroicon-o-building-storefront';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('name')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('location')
                    ->required()
                    ->maxLength(255),
                Forms\Components\TextInput::make('lat')
                    ->required()
                    ->numeric()
                    ->label('Latitude'),
                Forms\Components\TextInput::make('long')
                    ->required()
                    ->numeric()
                    ->label('Longitude'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->searchable(),
                Tables\Columns\TextColumn::make('location')
                    ->searchable(),
                Tables\Columns\TextColumn::make('lat')
                    ->label('Latitude')
                    ->sortable(),
                Tables\Columns\TextColumn::make('long')
                    ->label('Longitude')
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
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGasStations::route('/'),
            'create' => Pages\CreateGasStation::route('/create'),
            'edit' => Pages\EditGasStation::route('/{record}/edit'),
        ];
    }
}