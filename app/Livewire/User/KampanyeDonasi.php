<?php

namespace App\Livewire\User;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Services\CloudinaryImageService;
use App\Services\QrisDinamisService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.user')]
#[Title('Program Donasi & Kampanye Kebaikan - Yayasan Himatun Ayat Bandung')]
class KampanyeDonasi extends Component
{
    /**
     * Kunci session untuk menyimpan transaksi pembayaran yang sedang aktif.
     */
    public const ACTIVE_PAYMENT_SESSION_KEY = Donasi::ACTIVE_PAYMENT_SESSION_KEY;

    public ?int $selectedKampanyeId = null;

    public ?int $selectedNominal = null;

    public ?int $customNominal = null;

    public string $namaDonatur = '';

    public string $noTelepon = '';

    public ?string $email = '';

    public bool $isAnonim = false;

    public string $doaHarapan = '';

    public string $metodePembayaran = 'QRIS';

    #[Locked]
    public string $paymentId = '';

    #[Locked]
    public int $paymentStep = 1;

    public bool $showPaymentModal = false;

    public bool $showSuccessModal = false;

    public bool $showFailedModal = false;

    #[Locked]
    public ?int $paymentExpiresAt = null;

    #[Locked]
    public ?Donasi $currentDonasi = null;

    public int $activeSlide = 0;

    public array $bankAccounts = [
        [
            'bank' => 'BSI',
            'no_rekening' => '721.919.9827',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'Bank BSI',
        ],
    ];

    public function mount(?int $kampanye_id = null): void
    {
        $this->selectedKampanyeId = $this->resolveInitialKampanyeId($kampanye_id);

        $this->restoreActivePayment();
    }

    protected function resolveInitialKampanyeId(?int $kampanye_id = null): ?int
    {
        $programParam = $kampanye_id ?? request()->query('program') ?? request()->query('kampanye_id');

        if ($programParam !== null && $programParam !== '') {
            $matched = Kampanye::where('status', 'aktif')
                ->where(function ($query) use ($programParam) {
                    if (is_numeric($programParam)) {
                        $query->where('id', (int) $programParam);
                    } else {
                        $query->where('slug', (string) $programParam)
                            ->orWhere('judul', 'like', '%'.(string) $programParam.'%');
                    }
                })
                ->first();

            if ($matched) {
                return $matched->id;
            }
        }

        return Kampanye::where('status', 'aktif')->value('id');
    }

    /**
     * Simpan transaksi pembayaran yang sedang berjalan ke session agar pop-up
     * tetap tampil (tidak membuat transaksi baru) ketika halaman di-refresh.
     */
    protected function persistActivePayment(): void
    {
        if (! $this->currentDonasi || ! $this->paymentExpiresAt) {
            return;
        }

        session()->put(self::ACTIVE_PAYMENT_SESSION_KEY, [
            'donasi_id' => $this->currentDonasi->id,
            'payment_id' => $this->paymentId,
            'payment_step' => $this->paymentStep,
            'metode_pembayaran' => $this->metodePembayaran,
            'expires_at' => $this->paymentExpiresAt,
        ]);
    }

    protected function clearActivePayment(): void
    {
        session()->forget(self::ACTIVE_PAYMENT_SESSION_KEY);
    }

    /**
     * Pulihkan pop-up pembayaran dari session setelah halaman di-refresh.
     */
    protected function restoreActivePayment(): void
    {
        /** @var array{donasi_id: int, payment_id: string, payment_step: int, metode_pembayaran: string, expires_at: int}|null $active */
        $active = session(self::ACTIVE_PAYMENT_SESSION_KEY);

        if (! is_array($active) || empty($active['donasi_id'])) {
            return;
        }

        $donasi = Donasi::find($active['donasi_id']);

        if (! $donasi || $donasi->status !== 'pending' || $donasi->bukti_transfer_path) {
            $this->clearActivePayment();

            return;
        }

        $this->currentDonasi = $donasi;
        $this->paymentId = (string) $active['payment_id'];
        $this->metodePembayaran = (string) ($active['metode_pembayaran'] ?? 'QRIS');
        $this->selectedNominal = (int) round((float) $donasi->nominal);
        $this->customNominal = $this->selectedNominal;
        $this->selectedKampanyeId = $donasi->kampanye_id ?? $this->selectedKampanyeId;

        if ((int) $active['expires_at'] <= now()->timestamp) {
            Donasi::whereKey($donasi->id)->where('status', 'pending')
                ->whereNull('bukti_transfer_path')->update(['status' => 'rejected']);
            $this->clearActivePayment();
            $this->showPaymentModal = false;
            $this->showFailedModal = true;
            $this->paymentStep = 1;
            $this->paymentExpiresAt = null;

            return;
        }

        $this->paymentStep = in_array((int) $active['payment_step'], [1, 2], true) ? (int) $active['payment_step'] : 1;
        $this->paymentExpiresAt = (int) $active['expires_at'];
        $this->showFailedModal = false;
        $this->showPaymentModal = true;
    }

    public function selectKampanye(int $id, ?int $nominal = null): void
    {
        $this->selectedKampanyeId = $id;
        if ($nominal) {
            $this->selectedNominal = $nominal;
            $this->customNominal = $nominal;
        }
        $this->dispatch('scroll-to-form');
    }

    public function setNominal(int $amount): void
    {
        if ($this->selectedNominal === $amount) {
            $this->selectedNominal = null;
            $this->customNominal = null;
        } else {
            $this->selectedNominal = $amount;
            $this->customNominal = $amount;
            $this->resetValidation('selectedNominal');
        }
    }

    public function updatedCustomNominal($val): void
    {
        if ($val === null || $val === '') {
            $this->selectedNominal = null;
            $this->customNominal = null;

            return;
        }

        $intVal = (int) preg_replace('/[^0-9]/', '', (string) $val);
        $this->selectedNominal = $intVal > 0 ? $intVal : null;
        $this->customNominal = $this->selectedNominal;

        if ($this->selectedNominal >= 10000) {
            $this->resetValidation('selectedNominal');
        }
    }

    public function updatedIsAnonim($val): void
    {
        if ($val) {
            $this->namaDonatur = 'Hamba Allah';
            $this->resetValidation('namaDonatur');
        } else {
            $this->namaDonatur = '';
        }
    }

    public function updatedNamaDonatur(): void
    {
        $this->resetValidation('namaDonatur');
    }

    public function updatedNoTelepon(): void
    {
        $this->noTelepon = (string) preg_replace('/[^0-9]/', '', $this->noTelepon);
        $this->resetValidation('noTelepon');
    }

    public function updatedDoaHarapan(): void
    {
        $trimmed = trim($this->doaHarapan);
        $words = $trimmed === '' ? [] : preg_split('/\s+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);

        if (is_array($words) && count($words) <= 100) {
            $this->resetValidation('doaHarapan');
        }
    }

    public function nextSlide(int $total): void
    {
        $this->activeSlide = ($this->activeSlide + 1) % max(1, $total);
    }

    public function prevSlide(int $total): void
    {
        $this->activeSlide = ($this->activeSlide - 1 + $total) % max(1, $total);
    }

    public function setSlide(int $index): void
    {
        $this->activeSlide = $index;
    }

    public function confirmDonasi(): void
    {
        $this->validate([
            'selectedKampanyeId' => 'nullable|exists:kampanye,id',
            'namaDonatur' => 'required|string|min:2|max:100',
            'noTelepon' => 'required|digits_between:9,20',
            'email' => 'nullable|email|max:100',
            'selectedNominal' => 'required|numeric|min:10000',
            'doaHarapan' => [
                'nullable',
                'string',
                function (string $attribute, mixed $value, \Closure $fail): void {
                    $trimmed = trim((string) $value);
                    if ($trimmed !== '') {
                        $words = preg_split('/\s+/u', $trimmed, -1, PREG_SPLIT_NO_EMPTY);
                        if (is_array($words) && count($words) > 100) {
                            $fail('Doa & harapan maksimal 100 karakter.');
                        }
                    }
                },
            ],
        ], [
            'namaDonatur.required' => 'Mohon masukkan nama donatur atau centang Hamba Allah.',
            'noTelepon.required' => 'Nomor telepon/WhatsApp wajib diisi untuk konfirmasi.',
            'noTelepon.digits_between' => 'Nomor telepon/WhatsApp hanya boleh berisi angka (9–20 digit).',
            'selectedNominal.required' => 'Silakan pilih nominal donasi atau masukkan nominal lainnya.',
            'selectedNominal.min' => 'Minimal donasi adalah Rp 10.000.',
        ]);

        $finalNama = $this->isAnonim ? 'Hamba Allah' : trim($this->namaDonatur);

        $donasi = Donasi::create([
            'kampanye_id' => $this->selectedKampanyeId,
            'nama_donatur' => $finalNama,
            'no_whatsapp' => $this->noTelepon,
            'email' => $this->email ?: null,
            'nominal' => $this->selectedNominal,
            'metode_pembayaran' => $this->metodePembayaran,
            'bukti_transfer_path' => null,
            'pesan_doa' => $this->doaHarapan ?: null,
            'status' => 'pending',
        ]);

        $this->currentDonasi = $donasi;
        $this->paymentId = $this->generatePaymentId($donasi->id);
        $this->paymentStep = 1;
        $this->paymentExpiresAt = now()->addMinutes(5)->timestamp;
        $this->showFailedModal = false;
        $this->showPaymentModal = true;
        $this->persistActivePayment();
    }

    protected function generatePaymentId(?int $donasiId = null): string
    {
        do {
            $firstSegment = (string) random_int(10000000, 99999999);
            $secondSegment = str_pad((string) random_int(100, 999), 3, '0', STR_PAD_LEFT);
            $generated = "DNS-{$firstSegment}-{$secondSegment}";
        } while ($generated === $this->paymentId);

        return $generated;
    }

    public function selectMetodePembayaran(string $metode): void
    {
        if (! $this->currentDonasi?->hasActivePayment(session()->driver())) {
            return;
        }

        $this->metodePembayaran = $metode;
        $this->currentDonasi->update(['metode_pembayaran' => $metode]);
        $this->persistActivePayment();
    }

    public function payNow(): void
    {
        if (! $this->currentDonasi?->hasActivePayment(session()->driver())) {
            return;
        }
        $this->currentDonasi->update(['metode_pembayaran' => $this->metodePembayaran]);
        $this->paymentStep = 2;
        $this->persistActivePayment();
    }

    public function backToStepOne(): void
    {
        if (! $this->currentDonasi?->hasActivePayment(session()->driver())) {
            return;
        }

        $this->paymentStep = 1;
        $this->persistActivePayment();
    }

    public function closePaymentModal(): void
    {
        if (session(self::ACTIVE_PAYMENT_SESSION_KEY.'.donasi_id') === $this->currentDonasi?->id) {
            $this->clearActivePayment();
        }
        $this->showPaymentModal = false;
        $this->paymentStep = 1;
        $this->paymentExpiresAt = null;
    }

    public function expirePayment(): void
    {
        if (! $this->showPaymentModal || ! $this->currentDonasi
            || session(self::ACTIVE_PAYMENT_SESSION_KEY.'.donasi_id') !== $this->currentDonasi->id
            || session(self::ACTIVE_PAYMENT_SESSION_KEY.'.expires_at') > now()->timestamp) {
            return;
        }

        Donasi::whereKey($this->currentDonasi->id)->where('status', 'pending')
            ->whereNull('bukti_transfer_path')->update(['status' => 'rejected']);

        $this->showPaymentModal = false;
        $this->showFailedModal = true;
        $this->paymentStep = 1;
        $this->paymentExpiresAt = null;
        $this->clearActivePayment();
    }

    public function closeFailedModal(): void
    {
        $this->showFailedModal = false;
        $this->currentDonasi = null;
        $this->paymentStep = 1;
        $this->paymentExpiresAt = null;
    }

    public function uploadBuktiTransfer(array $reference, CloudinaryImageService $images): void
    {
        $request = request();
        abort_unless($this->currentDonasi?->allowsReceiptUpload(session()->driver()), 403);

        DB::transaction(function () use ($request, $reference, $images): void {
            $asset = $images->consume($request, $reference, 'buktiTransfer', $this->currentDonasi->id);
            $donasi = Donasi::lockForUpdate()->findOrFail($this->currentDonasi->id);
            abort_unless($donasi->allowsReceiptUpload(session()->driver()), 403);
            $donasi->forceFill([
                'bukti_transfer_path' => $asset['public_id'],
                'bukti_transfer_cloudinary' => $asset,
            ])->save();
            $this->currentDonasi = $donasi;
        });

        $this->closePaymentModal();
        $this->showSuccessModal = true;
    }

    public function closeSuccessModal(): void
    {
        $this->showSuccessModal = false;
        $this->resetForm();
    }

    public function resetForm(): void
    {
        $this->namaDonatur = '';
        $this->noTelepon = '';
        $this->email = '';
        $this->isAnonim = false;
        $this->doaHarapan = '';
        $this->selectedNominal = null;
        $this->customNominal = null;
        $this->currentDonasi = null;
        $this->paymentStep = 1;
        $this->metodePembayaran = 'QRIS';
    }

    public function getVirtualAccountDetailsProperty(): array
    {
        $nominal = (int) round((float) ($this->currentDonasi?->nominal ?? ($this->selectedNominal ?? 0)));

        if (in_array($this->metodePembayaran, ['Transfer Bank', 'Transfer Bank BSI'], true)) {
            return [
                'bank' => 'Bank BSI',
                'va_number' => '721.919.9827',
                'atas_nama' => 'Yayasan Himatun Ayat Bandung',
                'type' => 'transfer',
                'cara_bayar' => [
                    'Buka aplikasi BYOND by BSI, BSI Mobile, m-Banking bank lain, atau ATM.',
                    'Pilih menu Transfer ke Bank BSI dengan nomor rekening 721.919.9827 di atas.',
                    'Pastikan nominal sesuai (Rp '.number_format($nominal, 0, ',', '.').') atas nama Yayasan Himatun Ayat Bandung lalu selesaikan transaksi.',
                    'Unggah foto atau screenshot bukti transfer pada kolom di bawah ini.',
                ],
            ];
        }

        $qrisService = app(QrisDinamisService::class);
        $qrisPayload = $qrisService->generateDynamicPayload(max(10000, $nominal));

        return [
            'bank' => 'QRIS',
            'va_number' => null,
            'atas_nama' => QrisDinamisService::MERCHANT_NAME,
            'nmid' => QrisDinamisService::MERCHANT_NMID,
            'tid' => QrisDinamisService::MERCHANT_TID,
            'qris_payload' => $qrisPayload,
            'qris_image' => $qrisService->generateQrSvgDataUri($qrisPayload, 240),
            'type' => 'qris',
            'cara_bayar' => [
                'Buka aplikasi m-Banking atau e-Wallet favorit Anda.',
                'Pilih menu QRIS / Scan.',
                'Arahkan kamera ke kode QRIS Dinamis di atas (nominal Rp '.number_format($nominal, 0, ',', '.').' otomatis terisi).',
                'Konfirmasi pembayaran, lalu unggah bukti transaksi di bawah ini.',
            ],
        ];
    }

    public function getWhatsappLinkProperty(): string
    {
        $nominal = number_format($this->currentDonasi?->nominal ?? ($this->selectedNominal ?? 0), 0, ',', '.');
        $nama = $this->currentDonasi?->nama_donatur ?? ($this->isAnonim ? 'Hamba Allah' : ($this->namaDonatur ?: 'Donatur'));
        $kampanye = $this->currentDonasi?->kampanye?->judul ?? 'Program Donasi Umum';
        $pembayaranId = $this->paymentId ?: 'DNS-12345678-123';

        $text = "Assalamu'alaikum Warahmatullahi Wabarakatuh Pengurus Yayasan Himatun Ayat Bandung.\n\n"
            ."Saya ingin konfirmasi donasi:\n"
            ."• ID Pembayaran: {$pembayaranId}\n"
            ."• Nama: {$nama}\n"
            ."• Program: {$kampanye}\n"
            ."• Nominal: Rp {$nominal}\n"
            ."• Metode: {$this->metodePembayaran}\n\n"
            .'Semoga berkah dan bermanfaat bagi adik-adik santri. Terima kasih.';

        $whatsappAdmin = config('app.whatsapp_admin', '6283157371088');

        return 'https://wa.me/'.$whatsappAdmin.'?text='.urlencode($text);
    }

    public function render()
    {
        $kampanyeList = Kampanye::where('status', 'aktif')->get();

        $selectedKampanye = $this->selectedKampanyeId
            ? $kampanyeList->firstWhere('id', $this->selectedKampanyeId)
            : $kampanyeList->first();

        $recentDoa = Donasi::where('status', 'verified')
            ->whereNotNull('pesan_doa')
            ->where('pesan_doa', '!=', '')
            ->latest()
            ->take(12)
            ->get();

        return view('user.kampanye-donasi.kampanye-donasi', [
            'kampanyeList' => $kampanyeList,
            'selectedKampanye' => $selectedKampanye,
            'recentDoa' => $recentDoa,
        ]);
    }
}
