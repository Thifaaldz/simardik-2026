<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\SubKategoriDokumenResource\Pages;
use App\Filament\Admin\Clusters\ManajemenDokumenCluster;
use App\Models\SubKategoriDokumen;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class SubKategoriDokumenResource extends Resource
{
    protected static ?string $model = SubKategoriDokumen::class;

    protected static ?string $navigationIcon = 'heroicon-o-document-text';
    protected static ?string $navigationLabel = 'Sub Kategori Dokumen';
    protected static ?string $navigationGroup = 'Manajemen Arsip';
    protected static ?int $navigationSort = 2;
    protected static ?string $cluster = ManajemenDokumenCluster::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Select::make('kategori_dokumen_id')
                ->relationship('kategori', 'nama_kategori')
                ->required(),

            Forms\Components\TextInput::make('nama_sub_kategori')
                ->required(),

            Forms\Components\Textarea::make('deskripsi')
                ->columnSpanFull(),

            Forms\Components\Toggle::make('butuh_student')
                ->label('Butuh Data Siswa')
                ->default(false),

            Forms\Components\Toggle::make('butuh_pkl')
                ->label('Butuh Data PKL')
                ->default(false),

            Forms\Components\Toggle::make('butuh_pegawai')
                ->label('Butuh Data Pegawai')
                ->default(false),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kategori.nama_kategori')
                    ->label('Kategori'),

                Tables\Columns\TextColumn::make('nama_sub_kategori')
                    ->searchable(),

                Tables\Columns\IconColumn::make('butuh_student')
                    ->boolean()
                    ->label('Student'),

                Tables\Columns\IconColumn::make('butuh_pkl')
                    ->boolean()
                    ->label('PKL'),

                Tables\Columns\IconColumn::make('butuh_pegawai')
                    ->boolean()
                    ->label('Pegawai'),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListSubKategoriDokumens::route('/'),
            'create' => Pages\CreateSubKategoriDokumen::route('/create'),
            'edit' => Pages\EditSubKategoriDokumen::route('/{record}/edit'),
        ];
    }
}
