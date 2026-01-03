<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PklResource\Pages;
use App\Models\Pkl;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PklResource extends Resource
{
    protected static ?string $model = Pkl::class;

    protected static ?string $navigationIcon = 'heroicon-o-briefcase';
    protected static ?string $navigationLabel = 'PKL';
    protected static ?string $navigationGroup = 'PKL & Industri';
    protected static ?int $navigationSort = 2;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data PKL')
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->relationship('student', 'nama')
                        ->searchable()
                        ->required(),

                    // 'jurusan_id' not present in pkls migration; removed

                    Forms\Components\Select::make('dudi_id')
                        ->relationship('dudi', 'nama_dudi')
                        ->searchable()
                        ->required(),

                    // 'tahun_ajaran_id' not present in pkls migration; removed

                    Forms\Components\Select::make('pembimbing_id')
                        ->relationship('pembimbing', 'nama')
                        ->searchable()
                        ->required(),

                        Forms\Components\DatePicker::make('periode_mulai')
                            ->required(),

                        Forms\Components\DatePicker::make('periode_selesai')
                            ->required(),

                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Siswa')
                    ->searchable(),

                Tables\Columns\TextColumn::make('dudi.nama_dudi')
                    ->label('DUDI'),

                Tables\Columns\TextColumn::make('periode_mulai')
                    ->label('Mulai')
                    ->date(),

                Tables\Columns\TextColumn::make('periode_selesai')
                    ->label('Selesai')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListPkls::route('/'),
            'create' => Pages\CreatePkl::route('/create'),
            'edit' => Pages\EditPkl::route('/{record}/edit'),
        ];
    }
}
