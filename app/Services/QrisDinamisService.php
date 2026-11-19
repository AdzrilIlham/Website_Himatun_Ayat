<?php

namespace App\Services;

use BaconQrCode\Renderer\Image\SvgImageBackEnd;
use BaconQrCode\Renderer\ImageRenderer;
use BaconQrCode\Renderer\RendererStyle\RendererStyle;
use BaconQrCode\Writer;
use InvalidArgumentException;

class QrisDinamisService
{
    /**
     * Payload QRIS Statis resmi Yayasan Himatun Ayat Bandung (NMID: ID1026518070838).
     */
    public const DEFAULT_STATIC_QRIS = '00020101021126580013ID.CO.BRI.WWW01189360000200701649780208701649780303UMI51440014ID.CO.QRIS.WWW0215ID10265180708380303UMI5204866153033605802ID5925YAYASAN HIMATUN AYAT BAND6007BANDUNG61054062562070703A016304D1A6';

    public const MERCHANT_NAME = 'YAYASAN HIMATUN AYAT BANDUNG';

    public const MERCHANT_NMID = 'ID1026518070838';

    public const MERCHANT_TID = 'A01';

    /**
     * Mengonversi payload QRIS Statis menjadi QRIS Dinamis dengan nominal tertentu
     * sesuai spesifikasi EMVCo & referensi verssache/qris-dinamis.
     */
    public function generateDynamicPayload(int|float|string $nominal, ?string $staticQris = null): string
    {
        $staticQris = trim($staticQris ?: self::DEFAULT_STATIC_QRIS);
        $cleanNominal = (string) max(1, (int) round((float) $nominal));

        // 1. Hapus 4 karakter terakhir (checksum CRC16 statis)
        $qrisWithoutCrc = substr($staticQris, 0, -4);

        // 2. Ubah Point of Initiation Method dari Statis (010211) menjadi Dinamis (010212)
        $step1 = str_replace('010211', '010212', $qrisWithoutCrc);

        // 3. Pisahkan payload pada Tag 58 (Country Code: 5802ID)
        $parts = explode('5802ID', $step1);
        if (count($parts) !== 2) {
            throw new InvalidArgumentException('Format payload QRIS statis tidak valid (Tag 5802ID tidak ditemukan).');
        }

        // 4. Susun Tag 54 (Transaction Amount): "54" + 2 digit panjang nominal + nominal
        $tag54 = '54'.str_pad((string) strlen($cleanNominal), 2, '0', STR_PAD_LEFT).$cleanNominal;

        // 5. Gabungkan kembali dan hitung CRC16-CCITT baru
        $payloadWithoutCrc = trim($parts[0]).$tag54.'5802ID'.trim($parts[1]);

        return $payloadWithoutCrc.$this->calculateCrc16($payloadWithoutCrc);
    }

    /**
     * Menghitung checksum CRC16-CCITT (0xFFFF, polynomial 0x1021) sesuai standar EMVCo QRIS.
     */
    public function calculateCrc16(string $str): string
    {
        $crc = 0xFFFF;
        $strlen = strlen($str);

        for ($c = 0; $c < $strlen; $c++) {
            $crc ^= ord($str[$c]) << 8;
            for ($i = 0; $i < 8; $i++) {
                if ($crc & 0x8000) {
                    $crc = (($crc << 1) ^ 0x1021) & 0xFFFF;
                } else {
                    $crc = ($crc << 1) & 0xFFFF;
                }
            }
        }

        return strtoupper(str_pad(dechex($crc), 4, '0', STR_PAD_LEFT));
    }

    /**
     * Menghasilkan Data URI SVG (data:image/svg+xml;base64,...) dari payload QRIS Dinamis.
     */
    public function generateQrSvgDataUri(string $payload, int $size = 240): string
    {
        $svg = $this->renderQrSvg($payload, $size);

        return 'data:image/svg+xml;base64,'.base64_encode($svg);
    }

    /**
     * Menghasilkan markup SVG QR Code dari payload QRIS Dinamis.
     */
    public function renderQrSvg(string $payload, int $size = 240): string
    {
        $renderer = new ImageRenderer(
            new RendererStyle($size, 1),
            new SvgImageBackEnd
        );

        $writer = new Writer($renderer);

        return $writer->writeString($payload);
    }
}
