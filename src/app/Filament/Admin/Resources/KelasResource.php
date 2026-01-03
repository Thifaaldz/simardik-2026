<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\KelasResource\Pages;
use App\Models\Kelas;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class KelasResource extends Resource
{
    protected static ?string $model = Kelas::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-group';
    protected static ?string $navigationLabel = 'Kelas';
    protected static ?string $navigationGroup = 'Master Sekolah';
    protected static ?int $navigationSort = 4;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Kelas')
                ->schema([

                    Forms\Components\Select::make('jurusan_id')
                        ->label('Jurusan')
                        ->relationship('jurusan', 'nama_jurusan')
                        ->required(),

                    // 'sekolah_id' and 'tahun_ajaran_id' are not present in migrations for kelas; removed

                    Forms\Components\Select::make('tingkat')
                        ->label('Tingkat')
                        ->options([
                            'X' => 'X',
                            'XI' => 'XI',
                            'XII' => 'XII',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('nama_kelas')
                        ->label('Nama Kelas')
                        ->placeholder('Contoh: RPL 1')
                        ->required(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('tingkat')
                    ->label('Tingkat')
                    ->sortable(),

                Tables\Columns\TextColumn::make('nama_kelas')
                    ->label('Kelas')
                    ->searchable(),

                Tables\Columns\TextColumn::make('jurusan.nama_jurusan')
                    ->label('Jurusan'),

                // Tahun Ajaran and Sekolah columns removed to match migrations
            ])
            ->defaultSort('tingkat')
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
            'index' => Pages\ListKelas::route('/'),
            'create' => Pages\CreateKelas::route('/create'),
            'edit' => Pages\EditKelas::route('/{record}/edit'),
        ];
    }
}
