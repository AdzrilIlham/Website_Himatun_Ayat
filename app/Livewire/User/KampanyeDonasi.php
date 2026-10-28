<?php

namespace App\Livewire\User;

use App\Models\Donasi;
use App\Models\Kampanye;
use App\Services\CloudflareR2Service;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;
use Livewire\WithFileUploads;

#[Layout('layouts.app')]
#[Title('Program Donasi & Kampanye Kebaikan - Yayasan Himatun Ayat Bandung')]
class KampanyeDonasi extends Component
{
    use WithFileUploads;

    public ?int $selectedKampanyeId = null;

    public int $selectedNominal = 50000;

    public ?int $customNominal = 50000;

    public string $namaDonatur = '';

    public string $noTelepon = '';

    public ?string $email = '';

    public bool $isAnonim = false;

    public string $doaHarapan = '';

    public string $metodePembayaran = 'Transfer Bank (BCA)';

    public $buktiTransfer;

    public bool $showPaymentModal = false;

    public bool $showSuccessModal = false;

    public ?Donasi $currentDonasi = null;

    public int $activeSlide = 0;

    public array $bankAccounts = [
        [
            'bank' => 'BCA',
            'no_rekening' => '777-089-2341',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'Bank BCA',
        ],
        [
            'bank' => 'Mandiri',
            'no_rekening' => '130-00-1928374-1',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'Bank Mandiri',
        ],
        [
            'bank' => 'BSI',
            'no_rekening' => '718-293-8475',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'BSI Syariah',
        ],
        [
            'bank' => 'BRI',
            'no_rekening' => '0123-01-098765-53-0',
            'atas_nama' => 'Yayasan Himatun Ayat Bandung',
            'badge' => 'Bank BRI',
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
        $this->selectedNominal = $amount;
        $this->customNominal = $amount;
    }

    public function updatedCustomNominal($val): void
    {
        $intVal = (int) preg_replace('/[^0-9]/', '', (string) $val);
        $this->selectedNominal = $intVal;
    }

    public function updatedIsAnonim($val): void
    {
        if ($val) {
            $this->namaDonatur = 'Hamba Allah';
        } else {
            $this->namaDonatur = '';
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
            'noTelepon' => 'required|string|min:9|max:20',
            'email' => 'nullable|email|max:100',
            'selectedNominal' => 'required|numeric|min:10000',
            'doaHarapan' => 'nullable|string|max:1000',
        ], [
            'namaDonatur.required' => 'Mohon masukkan nama donatur atau centang Hamba Allah.',
            'noTelepon.required' => 'Nomor telepon/WhatsApp wajib diisi untuk konfirmasi.',
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
        $this->showPaymentModal = true;
    }

    public function uploadBuktiTransfer(CloudflareR2Service $r2Service): void
    {
        $this->validate([
            'buktiTransfer' => 'required|image|max:5120', // Max 5MB
        ], [
            'buktiTransfer.required' => 'Silakan pilih berkas bukti transfer (foto / screenshot).',
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

    public function completeWithoutUpload(): void
    {
        $this->showPaymentModal = false;
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
        $this->selectedNominal = 50000;
        $this->customNominal = 50000;
        $this->currentDonasi = null;
        $this->buktiTransfer = null;
    }

    public function getWhatsappLinkProperty(): string
    {
        $nominal = number_format($this->currentDonasi?->nominal ?? $this->selectedNominal, 0, ',', '.');
        $nama = $this->currentDonasi?->nama_donatur ?? ($this->isAnonim ? 'Hamba Allah' : ($this->namaDonatur ?: 'Donatur'));
        $kampanye = $this->currentDonasi?->kampanye?->judul ?? 'Program Donasi Umum';

        $text = "Assalamu'alaikum Warahmatullahi Wabarakatuh Pengurus Yayasan Himatun Ayat Bandung.\n\n"
            ."Saya ingin konfirmasi donasi:\n"
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

        return view('livewire.user.kampanye-donasi', [
            'kampanyeList' => $kampanyeList,
            'selectedKampanye' => $selectedKampanye,
            'recentDoa' => $recentDoa,
        ]);
    }
}
