<?php

namespace App\Filament\Resources\CompanyResource\RelationManagers;

use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VehiclesRelationManager extends RelationManager
{
    protected static string $relationship = 'vehicles';

    public function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('plate_number')
                    ->required()
                    ->maxLength(255)
                    ->label('Plate Number')
                    ->unique(ignorable: fn ($record) => $record)
                    ->placeholder('Enter the plate number'),

                Forms\Components\TextInput::make('model')
                    ->required()
                    ->maxLength(50)
                    ->label('Model')
                    ->placeholder('Enter vehicle model'),
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('plate_number')
            ->columns([
                Tables\Columns\TextColumn::make('plate_number')
                    ->searchable()
                    ->label('Plate #'),
                Tables\Columns\TextColumn::make('model')
                    ->searchable()
                    ->label('Model'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('Y-m-d H:i')
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                Tables\Actions\CreateAction::make()
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
