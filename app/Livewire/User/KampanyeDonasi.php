<?php

namespace App\Livewire\User;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Services\CloudflareR2Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.user')]
#[Title('Program Donasi & Kampanye Kebaikan - Yayasan Himatun Ayat Bandung')]
class KampanyeDonasi extends Component
{
    use WithFileUploads;

    public ?int $selectedKampanyeId = null;

    public ?int $selectedNominal = null;

    public ?int $customNominal = null;

    public string $namaDonatur = '';

    public string $noTelepon = '';

    public ?string $email = '';

    public bool $isAnonim = false;

    public string $doaHarapan = '';

    public string $metodePembayaran = 'QRIS';

    public string $paymentId = 'DNS-12345678-123';

    public int $paymentStep = 1;

    public $buktiTransfer;

    public bool $showPaymentModal = false;

    public bool $showSuccessModal = false;

    public bool $showFailedModal = false;

    public ?int $paymentExpiresAt = null;

    public ?Donasi $currentDonasi = null;

    public int $activeSlide = 0;

    public array $bankAccounts = [
        [
            'bank' => 'BRI',
            'no_rekening' => '0123-01-098765-53-0',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'Bank BRI',
        ],
        [
            'bank' => 'BCA',
            'no_rekening' => '777-089-2341',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'Bank BCA',
        ],
    ];

    public function mount(?int $kampanye_id = null): void
    {
        if ($kampanye_id) {
            $this->selectedKampanyeId = $kampanye_id;
        } else {
            $first = Kampanye::where('status', 'aktif')->first();
            $this->selectedKampanyeId = $first?->id;
        }
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
        $this->resetValidation('noTelepon');
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
            'noTelepon' => 'required|string|min:9|max:20',
            'email' => 'nullable|email|max:100',
            'selectedNominal' => 'required|numeric|min:10000',
            'doaHarapan' => 'nullable|string|max:1000',
        ], [
            'namaDonatur.required' => 'Mohon masukkan nama donatur atau centang Hamba Allah.',
            'noTelepon.required' => 'Nomor telepon/WhatsApp wajib diisi untuk konfirmasi.',
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
        $this->paymentId = 'DNS-'.str_pad((string) (12345000 + $donasi->id), 8, '0', STR_PAD_LEFT).'-'.str_pad((string) (($donasi->id * 37 + 123) % 900 + 100), 3, '0', STR_PAD_LEFT);
        $this->paymentStep = 1;
        $this->paymentExpiresAt = now()->addSeconds(60)->timestamp;
        $this->showFailedModal = false;
        $this->showPaymentModal = true;
    }

    public function selectMetodePembayaran(string $metode): void
    {
        $this->metodePembayaran = $metode;
        if ($this->currentDonasi) {
            $this->currentDonasi->update([
                'metode_pembayaran' => $metode,
            ]);
        }
    }

    public function payNow(): void
    {
        if ($this->currentDonasi) {
            $this->currentDonasi->update([
                'metode_pembayaran' => $this->metodePembayaran,
            ]);
        }
        $this->paymentStep = 2;
    }

    public function backToStepOne(): void
    {
        $this->paymentStep = 1;
    }

    public function closePaymentModal(): void
    {
        $this->showPaymentModal = false;
        $this->paymentStep = 1;
        $this->paymentExpiresAt = null;
    }

    public function expirePayment(): void
    {
        if (! $this->showPaymentModal) {
            return;
        }

        if ($this->currentDonasi && $this->currentDonasi->status === 'pending') {
            $this->currentDonasi->update([
                'status' => 'rejected',
            ]);
        }

        $this->showPaymentModal = false;
        $this->showFailedModal = true;
        $this->paymentStep = 1;
        $this->paymentExpiresAt = null;
        $this->reset(['buktiTransfer']);
    }

    public function closeFailedModal(): void
    {
        $this->showFailedModal = false;
        $this->currentDonasi = null;
        $this->buktiTransfer = null;
        $this->paymentStep = 1;
        $this->paymentExpiresAt = null;
    }

    public function updatedBuktiTransfer(): void
    {
        $this->resetValidation('buktiTransfer');
    }

    public function uploadBuktiTransfer(CloudflareR2Service $r2Service): void
    {
        $this->validate([
            'buktiTransfer' => 'required|image|max:5120', // Max 5MB
        ], [
            'buktiTransfer.required' => 'Wajib mengunggah foto atau screenshot bukti transfer untuk menyelesaikan pembayaran.',
            'buktiTransfer.image' => 'Berkas harus berupa gambar (JPG, PNG, atau WEBP).',
            'buktiTransfer.max' => 'Ukuran gambar maksimal 5MB.',
        ]);

        if ($this->currentDonasi && $this->buktiTransfer) {
            $path = $r2Service->uploadPrivate($this->buktiTransfer, 'bukti-transfer');

            $this->currentDonasi->update([
                'bukti_transfer_path' => $path,
                'metode_pembayaran' => $this->metodePembayaran,
            ]);

            // Add dana terkumpul update
            if ($this->currentDonasi->kampanye_id) {
                $kampanye = Kampanye::find($this->currentDonasi->kampanye_id);
                if ($kampanye) {
                    $kampanye->increment('dana_terkumpul', $this->currentDonasi->nominal);
                }
            }

            $this->showPaymentModal = false;
            $this->showSuccessModal = true;
            $this->reset(['buktiTransfer']);
        }
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
        $this->buktiTransfer = null;
        $this->paymentStep = 1;
        $this->metodePembayaran = 'QRIS';
    }

    public function getVirtualAccountDetailsProperty(): array
    {
        $nominal = $this->currentDonasi?->nominal ?? ($this->selectedNominal ?? 0);

        return match ($this->metodePembayaran) {
            'Transfer Bank BRI' => [
                'bank' => 'Bank BRI',
                'va_number' => '0123-01-098765-53-0',
                'atas_nama' => 'Yayasan Himatun Ayat Bandung',
                'type' => 'transfer',
                'cara_bayar' => [
                    'Buka aplikasi BRImo, m-Banking, atau ATM BRI.',
                    'Pilih menu Transfer ke nomor rekening BRI di atas.',
                    'Pastikan nominal sesuai (Rp '.number_format($nominal, 0, ',', '.').') lalu selesaikan transaksi.',
                    'Unggah foto atau screenshot bukti transfer pada kolom di bawah ini.',
                ],
            ],
            'Transfer Bank BCA' => [
                'bank' => 'Bank BCA',
                'va_number' => '777-089-2341',
                'atas_nama' => 'Yayasan Himatun Ayat Bandung',
                'type' => 'transfer',
                'cara_bayar' => [
                    'Buka aplikasi BCA Mobile, myBCA, atau ATM BCA.',
                    'Pilih menu m-Transfer / Transfer ke nomor rekening BCA di atas.',
                    'Pastikan nominal sesuai (Rp '.number_format($nominal, 0, ',', '.').') lalu selesaikan transaksi.',
                    'Unggah foto atau screenshot bukti transfer pada kolom di bawah ini.',
                ],
            ],
            default => [
                'bank' => 'QRIS',
                'va_number' => null,
                'atas_nama' => 'Yayasan Himatun Ayat Bandung',
                'type' => 'qris',
                'cara_bayar' => [
                    'Buka aplikasi m-Banking atau e-Wallet favorit Anda.',
                    'Pilih menu QRIS / Scan.',
                    'Arahkan kamera ke kode QRIS di bawah.',
                    'Konfirmasi nominal donasi, selesaikan pembayaran, lalu unggah bukti transfer di bawah ini.',
                ],
            ],
        };
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

        return 'https://wa.me/6281268765420?text='.urlencode($text);
    }

    public function render()
    {
        $kampanyeList = Kampanye::where('status', 'aktif')->get();

        $selectedKampanye = $this->selectedKampanyeId
            ? $kampanyeList->firstWhere('id', $this->selectedKampanyeId)
            : $kampanyeList->first();

        $recentDoa = Donasi::whereNotNull('pesan_doa')
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
