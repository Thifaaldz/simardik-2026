<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DudiResource\Pages;
use App\Models\Dudi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DudiResource extends Resource
{
    protected static ?string $model = Dudi::class;

    protected static ?string $navigationIcon = 'heroicon-o-building-office';
    protected static ?string $navigationLabel = 'DUDI';
    protected static ?string $navigationGroup = 'PKL & Industri';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data DUDI')
                ->schema([
                    Forms\Components\TextInput::make('nama_dudi')
                        ->label('Nama Perusahaan')
                        ->required(),

                    Forms\Components\TextInput::make('bidang_usaha')
                        ->required(),

                    Forms\Components\TextInput::make('alamat')
                        ->columnSpanFull(),

                    Forms\Components\TextInput::make('nama_pembimbing')
                        ->label('Pembimbing Industri'),

                    Forms\Components\TextInput::make('no_hp')
                        ->label('No HP'),

                    Forms\Components\TextInput::make('email')
                        ->email(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_dudi')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('bidang_usaha'),

                Tables\Columns\TextColumn::make('nama_pembimbing')
                    ->label('Pembimbing'),

                Tables\Columns\TextColumn::make('no_hp'),
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

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDudis::route('/'),
            'create' => Pages\CreateDudi::route('/create'),
            'edit' => Pages\EditDudi::route('/{record}/edit'),
        ];
    }
}
