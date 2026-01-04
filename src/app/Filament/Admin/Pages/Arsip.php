<?php

namespace App\Filament\Admin\Pages;

use App\Models\Document;
use App\Models\KategoriDokumen;
use App\Models\SubKategoriDokumen;
use App\Models\TahunAjaran;
use App\Filament\Admin\Clusters\ArsipCluster;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Forms;
use Filament\Notifications\Notification;

class Arsip extends Page implements HasTable
{
    use InteractsWithTable;

    protected static string $view = 'filament.admin.pages.arsip';
    protected static ?string $navigationIcon = 'heroicon-o-archive-box';
    protected static ?string $navigationLabel = 'Arsip';
    protected static ?string $navigationGroup = 'Manajemen Arsip';
    protected static ?int $navigationSort = 0;
    protected static ?string $cluster = ArsipCluster::class;

    public ?int $selectedKategori = null;
    public ?int $selectedSubKategori = null;

    public function mount(): void
    {
        $this->selectedKategori = request()->query('selectedKategori') ? (int) request()->query('selectedKategori') : null;
        $this->selectedSubKategori = request()->query('selectedSubKategori') ? (int) request()->query('selectedSubKategori') : null;
    }

    public function getSubNavigation(): array
    {
        $groups = [];

        $kategoris = KategoriDokumen::with('subKategori')->get();

        foreach ($kategoris as $kategori) {
            $items = [];
            foreach ($kategori->subKategori as $sub) {
                $items[] = NavigationItem::make($sub->nama_sub_kategori)
                    ->url(fn () => self::getUrl(['selectedKategori' => $kategori->id, 'selectedSubKategori' => $sub->id]));
            }

            $groups[] = NavigationGroup::make($kategori->nama_kategori)->items($items);
        }

        return $groups;
    }

    public function selectKategori(?int $id)
    {
        $this->selectedKategori = $id;
        $this->selectedSubKategori = null;
        $this->resetTablePage();
    }

    public function selectSubKategori(?int $id)
    {
        $this->selectedSubKategori = $id;
        $this->resetTablePage();
    }

    public function getKategoriList()
    {
        return KategoriDokumen::with('subKategori')->get();
    }

    protected function getTableQuery()
    {
        return Document::query()
            ->when($this->selectedKategori, fn ($q) => $q->whereHas('subKategori', fn ($q2) => $q2->where('kategori_dokumen_id', $this->selectedKategori)))
            ->when($this->selectedSubKategori, fn ($q) => $q->where('sub_kategori_dokumen_id', $this->selectedSubKategori));
    }

    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('kode_dokumen')->searchable(),
            Tables\Columns\TextColumn::make('nama_dokumen')->label('Judul')->searchable(),
            Tables\Columns\TextColumn::make('subKategori.nama_sub_kategori')->label('Jenis'),
            Tables\Columns\TextColumn::make('student.nama')->label('Siswa'),
            Tables\Columns\TextColumn::make('pegawai.nama')->label('Pegawai'),
            Tables\Columns\TextColumn::make('created_at')->date(),
        ];
    }

    protected function getTableActions(): array
    {
        return [
            Tables\Actions\ViewAction::make(),
            Tables\Actions\EditAction::make(),
        ];
    }

    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label('Upload Arsip')
                ->form([
                    Forms\Components\TextInput::make('kode_dokumen')->required(),
                    Forms\Components\Select::make('sub_kategori_dokumen_id')
                        ->label('Jenis Dokumen')
                        ->options(fn () => SubKategoriDokumen::when($this->selectedKategori, fn ($q) => $q->where('kategori_dokumen_id', $this->selectedKategori))->pluck('nama_sub_kategori', 'id')->toArray())
                        ->default(fn () => $this->selectedSubKategori)
                        ->reactive()
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
                        ->options(fn () => TahunAjaran::pluck('tahun', 'tahun')->toArray()),

                    Forms\Components\TextInput::make('judul')->required(),
                    Forms\Components\DatePicker::make('tanggal_dokumen')->required(),
                    Forms\Components\FileUpload::make('file_path')
                    ->label('File Dokumen (PDF)')
                    ->disk('local')
                    ->directory('arsip-dokumen')
                    ->acceptedFileTypes([
                        'application/pdf',
                        'application/x-pdf',
                        'application/octet-stream',
                    ])
                    ->rules([
                        'required',
                        'file',
                        'mimes:pdf',
                        'max:20480', // 20MB
                    ])
                    ->required()
                ,
                    Forms\Components\Textarea::make('keterangan')->columnSpanFull(),
                ])
                ->action(function (array $data) {
                    if (! empty($data['sub_kategori_dokumen_id'])) {
                        $sub = SubKategoriDokumen::find($data['sub_kategori_dokumen_id']);
                        if ($sub) {
                            $data['kategori_dokumen_id'] = $sub->kategori_dokumen_id;
                        }
                    }

                    Document::create($data);

                    Notification::make()->success()->title('Arsip berhasil diunggah')->send();
                }),
        ];
    }
}
