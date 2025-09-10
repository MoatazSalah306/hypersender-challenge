<?php

namespace App\Filament\Resources\DriverResource\RelationManagers;

use App\Enums\TripStatus;
use App\Models\Trip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
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
                    ->options(TripStatus::options())
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
                    ->options(TripStatus::options()),
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
                    ->before(function (array $data, Tables\Actions\CreateAction $action) {
                        if (Trip::hasOverlap($data)) {
                            Notification::make()
                                ->title('⚠ Overlap Detected')
                                ->body('This driver or vehicle is already booked for the selected time.')
                                ->danger()
                                ->send();

                            $action->halt();
                        }

                        return $data;
                    })
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
