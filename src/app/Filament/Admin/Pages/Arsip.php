<?php

namespace App\Filament\Admin\Pages;

use App\Models\Document;
use App\Models\KategoriDokumen;
use App\Models\SubKategoriDokumen;
use App\Filament\Admin\Clusters\ArsipCluster;
use App\Filament\Admin\Resources\DocumentResource;
use Filament\Navigation\NavigationGroup;
use Filament\Navigation\NavigationItem;
use Filament\Pages\Page;
use Filament\Tables;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Concerns\InteractsWithTable;
use Filament\Notifications\Notification;
use Filament\Forms;

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

    /* =====================================================
     *  MOUNT
     * ===================================================== */
    public function mount(): void
    {
        $this->selectedKategori = request('selectedKategori');
        $this->selectedSubKategori = request('selectedSubKategori');
    }

    /* =====================================================
     *  SUB NAVIGATION
     * ===================================================== */
    public function getSubNavigation(): array
    {
        return KategoriDokumen::with('subKategori')->get()
            ->map(function ($kategori) {
                return NavigationGroup::make($kategori->nama_kategori)
                    ->items(
                        $kategori->subKategori->map(fn ($sub) =>
                            NavigationItem::make($sub->nama_sub_kategori)
                                ->url(fn () => self::getUrl([
                                    'selectedKategori'    => $kategori->id,
                                    'selectedSubKategori' => $sub->id,
                                ]))
                        )->toArray()
                    );
            })
            ->toArray();
    }

    /* =====================================================
     *  QUERY
     * ===================================================== */
    protected function getTableQuery()
    {
        return Document::query()
            ->when(
                $this->selectedKategori,
                fn ($q) => $q->whereHas(
                    'subKategori',
                    fn ($sq) => $sq->where(
                        'kategori_dokumen_id',
                        $this->selectedKategori
                    )
                )
            )
            ->when(
                $this->selectedSubKategori,
                fn ($q) => $q->where(
                    'sub_kategori_dokumen_id',
                    $this->selectedSubKategori
                )
            );
    }

    /* =====================================================
     *  COLUMNS
     * ===================================================== */
    protected function getTableColumns(): array
    {
        return [
            Tables\Columns\TextColumn::make('kode_dokumen')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('nama_dokumen')
                ->searchable()
                ->sortable(),

            Tables\Columns\TextColumn::make('subKategori.nama_sub_kategori')
                ->label('Jenis'),

            Tables\Columns\TextColumn::make('created_at')
                ->label('Tanggal')
                ->date(),
        ];
    }

    /* =====================================================
     *  ROW ACTIONS (VIEW / EDIT / DOWNLOAD)
     * ===================================================== */
protected function getTableActions(): array
{
    return [
        /**
         * ================= VIEW =================
         */
        Tables\Actions\Action::make('view')
            ->label('Lihat')
            ->icon('heroicon-o-eye')
            ->url(fn (Document $record) =>
                DocumentResource::getUrl('view', ['record' => $record])
            ),

        /**
         * ================= EDIT =================
         */
        Tables\Actions\Action::make('edit')
            ->label('Edit')
            ->icon('heroicon-o-pencil-square')
            ->url(fn (Document $record) =>
                DocumentResource::getUrl('edit', ['record' => $record])
            ),

        /**
         * ================= DOWNLOAD =================
         */
        Tables\Actions\Action::make('download')
            ->label('Download')
            ->icon('heroicon-o-arrow-down-tray')
            ->url(fn (Document $record) =>
                route('documents.download', $record)
            )
            ->openUrlInNewTab()
            ->visible(fn (Document $record) =>
                filled($record->file_path)
            ),
    ];
}


    /* =====================================================
     *  HEADER ACTION — CREATE (UPLOAD ARSIP)
     * ===================================================== */
    protected function getTableHeaderActions(): array
    {
        return [
            Tables\Actions\CreateAction::make()
                ->label('Upload Arsip')
                ->model(Document::class)

                /**
                 * FORM = 1 SUMBER KEBENARAN
                 */
                ->form(
                    DocumentResource::documentFormSchema()
                )

                /**
                 * PREFILL SAAT MODAL DIBUKA
                 */
                ->mountUsing(function (Forms\ComponentContainer $form) {
                    if ($this->selectedSubKategori) {
                        $form->fill([
                            'sub_kategori_dokumen_id' => $this->selectedSubKategori,
                        ]);
                    }
                })

                /**
                 * MUTATE DATA SAAT SUBMIT
                 */
                ->mutateFormDataUsing(function (array $data) {

                    // Lock sub kategori dari context
                    if ($this->selectedSubKategori) {
                        $data['sub_kategori_dokumen_id'] = $this->selectedSubKategori;
                    }

                    // Set kategori otomatis
                    if (!empty($data['sub_kategori_dokumen_id'])) {
                        $sub = SubKategoriDokumen::with('kategori')
                            ->find($data['sub_kategori_dokumen_id']);

                        $data['kategori_dokumen_id'] = $sub?->kategori?->id;
                    }

                    // Default value
                    $data['status_dokumen'] ??= 'aktif';
                    $data['tingkat_kerahasiaan'] ??= 'publik';
                    $data['created_by'] = auth()->id();

                    return $data;
                })

                /**
                 * NOTIFICATION
                 */
                ->after(function () {
                    Notification::make()
                        ->success()
                        ->title('Arsip berhasil diunggah')
                        ->send();
                }),
        ];
    }
}
