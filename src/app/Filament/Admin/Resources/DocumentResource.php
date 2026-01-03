<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DocumentResource\Pages;
use App\Filament\Admin\Clusters\ManajemenDokumenCluster;
use App\Models\Document;
use App\Models\SubKategoriDokumen;
use App\Models\TahunAjaran;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class DocumentResource extends Resource
{
    protected static ?string $model = Document::class;

    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Dokumen';
    protected static ?string $navigationGroup = 'Manajemen Arsip';
    protected static ?int $navigationSort = 3;
    protected static ?string $cluster = ManajemenDokumenCluster::class;

    public static function form(Form $form): Form
    {
        return $form->schema([
            Forms\Components\Section::make('Informasi Dokumen')
                ->schema([
                    Forms\Components\TextInput::make('kode_dokumen')
                        ->label('Kode Dokumen')
                        ->required(),

                    Forms\Components\Select::make('sub_kategori_dokumen_id')
                        ->label('Jenis Dokumen')
                            ->relationship('subKategori', 'nama_sub_kategori')
                        ->reactive()
                        ->required(),
                ])->columns(2),

            Forms\Components\Section::make('Relasi Dokumen')
                ->schema([
                    Forms\Components\Select::make('student_id')
                        ->relationship('student', 'nama')
                        ->searchable()
                        ->visible(fn ($get) =>
                            optional(SubKategoriDokumen::find($get('sub_kategori_dokumen_id')))
                                ->butuh_student
                        ),

                    Forms\Components\Select::make('pegawai_id')
                        ->relationship('pegawai', 'nama')
                        ->searchable()
                        ->visible(fn ($get) =>
                            optional(SubKategoriDokumen::find($get('sub_kategori_dokumen_id')))
                                ->butuh_pegawai
                        ),

                    Forms\Components\Select::make('pkl_id')
                        ->relationship('pkl', 'id')
                        ->visible(fn ($get) =>
                            optional(SubKategoriDokumen::find($get('sub_kategori_dokumen_id')))
                                ->butuh_pkl
                        ),

                    Forms\Components\Select::make('tahun')
                        ->label('Tahun')
                        ->options(TahunAjaran::pluck('tahun', 'tahun')->toArray()),
                ])->columns(2),

            Forms\Components\Section::make('File Arsip')
                ->schema([
                    Forms\Components\TextInput::make('judul')
                        ->required(),

                    Forms\Components\DatePicker::make('tanggal_dokumen')
                        ->required(),

                    Forms\Components\DatePicker::make('expires_at')
                        ->label('Tanggal Kadaluarsa (Opsional)')
                        ->helperText('Jika diisi, file akan di-prune ketika melewati tanggal ini.'),

                    Forms\Components\FileUpload::make('file_path')
                        ->directory('arsip-dokumen')
                        ->disk('local')
                        ->acceptedFileTypes(['application/pdf'])
                        ->required(),

                    Forms\Components\Textarea::make('keterangan')
                        ->columnSpanFull(),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_dokumen')
                    ->searchable(),

                Tables\Columns\TextColumn::make('subKategori.nama_sub_kategori')
                    ->label('Jenis'),

                Tables\Columns\TextColumn::make('student.nama')
                    ->label('Siswa'),

                Tables\Columns\TextColumn::make('pegawai.nama')
                    ->label('Pegawai'),

                Tables\Columns\TextColumn::make('created_at')
                    ->date(),
            ])
            ->actions([
                Tables\Actions\ViewAction::make(),
                Tables\Actions\Action::make('download')
                    ->label('Download')
                    ->icon('heroicon-o-arrow-down-tray')
                    ->url(fn (Document $record): string => route('documents.download', $record))
                    ->visible(fn (Document $record): bool => (bool) $record->file_path),
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\DeleteBulkAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListDocuments::route('/'),
            'create' => Pages\CreateDocument::route('/create'),
            'edit' => Pages\EditDocument::route('/{record}/edit'),
        ];
    }
}
