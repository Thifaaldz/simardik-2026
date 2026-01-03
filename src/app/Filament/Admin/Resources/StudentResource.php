<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\StudentResource\Pages;
use App\Models\Student;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class StudentResource extends Resource
{
    protected static ?string $model = Student::class;

    protected static ?string $navigationIcon = 'heroicon-o-academic-cap';
    protected static ?string $navigationLabel = 'Siswa';
    protected static ?string $navigationGroup = 'Akademik';
    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Data Induk Siswa')
                ->schema([
                    Forms\Components\TextInput::make('nis')
                        ->label('NIS')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Forms\Components\TextInput::make('nisn')
                        ->label('NISN')
                        ->required()
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

                    Forms\Components\Select::make('jurusan_id')
                        ->relationship('jurusan', 'nama_jurusan')
                        ->required()
                        ->searchable(),

                    Forms\Components\Select::make('kelas_id')
                        ->relationship('kelas', 'nama_kelas')
                        ->required()
                        ->searchable(),

                    Forms\Components\Select::make('tahun_ajaran_id')
                        ->relationship('tahunAjaran', 'nama_tahun')
                        ->required(),

                    Forms\Components\TextInput::make('angkatan')
                        ->numeric(),

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
                Tables\Columns\TextColumn::make('nis')
                    ->label('NIS')
                    ->searchable(),

                Tables\Columns\TextColumn::make('nama')
                    ->searchable()
                    ->sortable(),

                Tables\Columns\TextColumn::make('jurusan.nama_jurusan')
                    ->label('Jurusan'),

                Tables\Columns\TextColumn::make('kelas.nama_kelas')
                    ->label('Kelas'),

                Tables\Columns\TextColumn::make('tahunAjaran.nama_tahun')
                    ->label('Tahun Ajaran'),
            ])
            ->filters([
                Tables\Filters\SelectFilter::make('jurusan_id')
                    ->relationship('jurusan', 'nama_jurusan')
                    ->label('Jurusan'),

                Tables\Filters\SelectFilter::make('kelas_id')
                    ->relationship('kelas', 'nama_kelas')
                    ->label('Kelas'),
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
            'index' => Pages\ListStudents::route('/'),
            'create' => Pages\CreateStudent::route('/create'),
            'edit' => Pages\EditStudent::route('/{record}/edit'),
        ];
    }
}
