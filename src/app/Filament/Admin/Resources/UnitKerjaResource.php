<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\UnitKerjaResource\Pages;
use App\Models\UnitKerja;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class UnitKerjaResource extends Resource
{
    protected static ?string $model = UnitKerja::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'Unit Kerja';
    protected static ?string $navigationGroup = 'Master Sekolah';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Unit Kerja')
                ->schema([
                    // 'sekolah_id' not present in migrations; removed to match DB schema

                    Forms\Components\TextInput::make('nama_unit')
                        ->label('Nama Unit Kerja')
                        ->required()
                        ->maxLength(100),

                    Forms\Components\Select::make('jenis_unit')
                        ->label('Jenis Unit')
                        ->options([
                            'akademik' => 'Akademik',
                            'non-akademik' => 'Non-Akademik',
                        ])
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nama_unit')
                    ->label('Unit Kerja')
                    ->searchable()
                    ->sortable(),

                // Sekolah column removed to match migrations (no sekolah_id on unit_kerjas)

                Tables\Columns\TextColumn::make('created_at')
                    ->label('Dibuat')
                    ->date('d M Y'),
            ])
            ->defaultSort('nama_unit')
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
            'index' => Pages\ListUnitKerjas::route('/'),
            'create' => Pages\CreateUnitKerja::route('/create'),
            'edit' => Pages\EditUnitKerja::route('/{record}/edit'),
        ];
    }
}
