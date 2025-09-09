<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use App\Enums\TripStatus;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TripsRelationManager extends RelationManager
{
    protected static string $relationship = 'trips';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->required()
                    ->label('Driver')
                    ->placeholder('Choose a driver'),

                Forms\Components\Select::make('vehicle_id')
                    ->relationship('vehicle', 'plate_number')
                    ->required()
                    ->label('Vehicle')
                    ->placeholder('Choose a vehicle '),

                Forms\Components\DateTimePicker::make('start_time')
                    ->required()
                    ->label('Start Time'),

                Forms\Components\DateTimePicker::make('end_time')
                    ->required()
                    ->after('start_time')
                    ->label('End Time'),

                Forms\Components\Select::make('status')
                    ->options(TripStatus::values())
                    ->required()
                    ->label('Status')
                    ->placeholder('Choose a status'),

                Forms\Components\Textarea::make('description')
                    ->maxLength(500)
                    ->label('Description')
                    ->placeholder('Enter trip description (optional)'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('status')
            ->columns([
                Tables\Columns\TextColumn::make('driver.name')
                    ->searchable()
                    ->label('Driver'),
                Tables\Columns\TextColumn::make('vehicle.plate_number')
                    ->searchable()
                    ->label('Vehicle'),
                Tables\Columns\TextColumn::make('start_time')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->label('Start'),
                Tables\Columns\TextColumn::make('end_time')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->label('End'),
                Tables\Columns\TextColumn::make('status')
                    ->searchable()
                    ->label('Status'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('status')
                    ->options(TripStatus::values()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make(),
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
}
