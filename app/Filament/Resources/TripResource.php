<?php

namespace App\Filament\Resources;

use App\Enums\TripStatus;
use App\Filament\Resources\TripResource\Pages;
use App\Filament\Resources\TripResource\RelationManagers;
use App\Models\Trip;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class TripResource extends Resource
{
    protected static ?string $model = Trip::class;

    protected static ?string $navigationIcon = 'heroicon-o-map';
    protected static ?string $navigationGroup = 'Operations';



    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('company_id')
                    ->relationship('company', 'name')
                    ->required()
                    ->label('Company')
                    ->placeholder('Choose a company '),
                Forms\Components\Select::make('driver_id')
                    ->relationship('driver', 'name')
                    ->required()
                    ->label('Driver')
                    ->placeholder('Choose a driver '),
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

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('company.name')
                    ->searchable()
                    ->label('Company'),
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
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
            ])
            ->modifyQueryUsing(fn (Builder $query) => $query->with(['company', 'driver', 'vehicle']));
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
            'index' => Pages\ListTrips::route('/'),
            'create' => Pages\CreateTrip::route('/create'),
            'edit' => Pages\EditTrip::route('/{record}/edit'),
        ];
    }

    public static function shouldRegisterNavigation(): bool
    {
        return true;
    }

    public static function getNavigationBadge(): ?string
    {
        return Trip::activeNow()->count();
    }

   
}
