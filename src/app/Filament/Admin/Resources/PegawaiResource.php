<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PegawaiResource\Pages;
use App\Models\Pegawai;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class PegawaiResource extends Resource
{
    protected static ?string $model = Pegawai::class;

    protected static ?string $navigationIcon = 'heroicon-o-user-group';
    protected static ?string $navigationLabel = 'Pegawai';
    protected static ?string $navigationGroup = 'Manajemen SDM';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Pegawai')
                ->schema([
                    Forms\Components\TextInput::make('nip')
                        ->label('NIP')
                        ->maxLength(25)
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('nama')
                        ->label('Nama Lengkap')
                        ->required(),

                    Forms\Components\Select::make('jenis_kelamin')
                        ->options([
                            'L' => 'Laki-laki',
                            'P' => 'Perempuan',
                        ])
                        ->required(),

                    Forms\Components\TextInput::make('jabatan')
                        ->label('Jabatan')
                        ->placeholder('Guru Produktif, TU, Waka Kurikulum'),

                    Forms\Components\Select::make('unit_kerja_id')
                        ->label('Unit Kerja')
                        ->relationship('unitKerja', 'nama_unit')
                        ->searchable()
                        ->required(),

                    Forms\Components\TextInput::make('email')
                        ->email(),

                    Forms\Components\TextInput::make('no_hp')
                        ->label('No HP'),

                    Forms\Components\Textarea::make('alamat')
                        ->columnSpanFull(),
                ])
                ->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('nip')
                    ->label('NIP')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->label('Nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jabatan')
                    ->label('Jabatan'),

                Tables\Columns\TextColumn::make('unitKerja.nama_unit')
                    ->label('Unit Kerja'),

                Tables\Columns\TextColumn::make('no_hp')
                    ->label('Kontak'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('unit_kerja_id')
                    ->relationship('unitKerja', 'nama_unit')
                    ->label('Unit Kerja'),
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
            'index' => Pages\ListPegawais::route('/'),
            'create' => Pages\CreatePegawai::route('/create'),
            'edit' => Pages\EditPegawai::route('/{record}/edit'),
        ];
    }
}
