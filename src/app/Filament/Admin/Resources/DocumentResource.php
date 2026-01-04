<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\DocumentResource\Pages;
use App\Filament\Admin\Clusters\ManajemenDokumenCluster;
use App\Models\Document;
use App\Models\KategoriDokumen;
use App\Models\SubKategoriDokumen;
use App\Models\TahunAjaran;
use App\Models\UnitKerja;
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
            // INFORMASI DOKUMEN
            Forms\Components\Section::make('Informasi Dokumen')
                ->schema([
                    Forms\Components\Select::make('sub_kategori_dokumen_id')
                        ->label('Jenis Dokumen')
                        ->relationship('subKategori', 'nama_sub_kategori')
                        ->reactive()
                        ->required()
                        ->afterStateUpdated(function ($set, $state) {
                            $subKategori = SubKategoriDokumen::with('kategori')->find($state);

                            // Set kategori_dokumen_id otomatis (use ID, not name)
                            $set('kategori_dokumen_id', $subKategori?->kategori?->id);

                            // Generate kode dokumen otomatis
                            $last = Document::latest('id')->first();
                            $number = $last ? intval(substr($last->kode_dokumen, strrpos($last->kode_dokumen, '-') + 1)) + 1 : 1;
                            $prefix = $subKategori?->kode_prefix ?? 'DOC';
                            $kode = $prefix . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
                            $set('kode_dokumen', $kode);

                            // Generate nama dokumen otomatis
                            $set('nama_dokumen', ($subKategori?->nama_sub_kategori ?? 'Dokumen') . ' - ' . $kode);
                        }),

                    Forms\Components\Select::make('kategori_dokumen_id')
                        ->label('Kategori Dokumen')
                        ->options(function () {
                            return \App\Models\KategoriDokumen::pluck('nama_kategori', 'id')->toArray();
                        })
                        ->required()
                        ->disabled(),

                    Forms\Components\TextInput::make('kode_dokumen')
                        ->label('Kode Dokumen')
                        ->required()
                        ->readOnly(),

                    Forms\Components\TextInput::make('nama_dokumen')
                        ->label('Nama Dokumen')
                        ->required()
                        ->readOnly(),
                ])->columns(2),

            // RELASI
            Forms\Components\Section::make('Relasi Dokumen')
                ->schema([
                    Forms\Components\Select::make('unit_kerja_id')
                        ->label('Unit Kerja')
                        ->relationship('unitKerja', 'nama_unit')
                        ->required(),

                    Forms\Components\Select::make('student_id')
                        ->relationship('student', 'nama')
                        ->searchable()
                        ->visible(fn ($get) => optional(SubKategoriDokumen::find($get('sub_kategori_dokumen_id')))->butuh_student),

                    Forms\Components\Select::make('pegawai_id')
                        ->relationship('pegawai', 'nama')
                        ->searchable()
                        ->visible(fn ($get) => optional(SubKategoriDokumen::find($get('sub_kategori_dokumen_id')))->butuh_pegawai),

                    Forms\Components\Select::make('pkl_id')
                        ->relationship('pkl', 'id')
                        ->visible(fn ($get) => optional(SubKategoriDokumen::find($get('sub_kategori_dokumen_id')))->butuh_pkl),

                    Forms\Components\Select::make('tahun')
                        ->label('Tahun')
                        ->options(TahunAjaran::pluck('tahun', 'tahun')->toArray()),
                ])->columns(2),

    // FILE ARSIP
    Forms\Components\Section::make('File Arsip')
        ->schema([
            Forms\Components\DatePicker::make('tanggal_dokumen')
                ->required(),

            Forms\Components\DatePicker::make('expires_at')
                ->label('Tanggal Kadaluarsa (Opsional)')
                ->helperText('Jika diisi, file akan di-prune ketika melewati tanggal ini.'),

            Forms\Components\FileUpload::make('file_path')
                ->label('File Dokumen')
                ->directory('arsip-dokumen')
                ->disk('local')
                ->acceptedFileTypes(['application/pdf'])
                ->rules(['mimes:pdf'])
                ->required()
                ->enableOpen()
                ->enableDownload(),

            Forms\Components\Hidden::make('status_dokumen')
                ->default('aktif'),

            Forms\Components\Hidden::make('tingkat_kerahasiaan')
                ->default('publik'),
        ]),
]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('kode_dokumen')->searchable(),
                Tables\Columns\TextColumn::make('nama_dokumen')->searchable(),
                Tables\Columns\TextColumn::make('subKategori.nama_sub_kategori')->label('Jenis'),
                Tables\Columns\TextColumn::make('kategori.nama')->label('Kategori'),
                Tables\Columns\TextColumn::make('student.nama')->label('Siswa'),
                Tables\Columns\TextColumn::make('pegawai.nama')->label('Pegawai'),
                Tables\Columns\TextColumn::make('created_at')->date(),
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
