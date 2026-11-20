<?php

namespace App\Http\Controllers;

use App\Models\AnakAsuh;
use App\Models\BeritaKegiatan;
use App\Models\Donasi;
use App\Models\Kampanye;
use App\Services\CloudflareR2Service;
use App\Services\CloudinaryImageService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class ImageUploadController extends Controller
{
    public function admin(Request $request, CloudinaryImageService $images): JsonResponse
    {
        $data = $request->validate([
            'purpose' => ['required', Rule::in(CloudinaryImageService::ADMIN_PURPOSES)],
            'record_id' => ['nullable', 'integer', 'min:1'],
            'bytes' => ['required', 'integer'],
            'mime' => ['required', 'string'],
        ]);
        $recordId = isset($data['record_id']) ? (int) $data['record_id'] : null;
        if ($recordId !== null) {
            $exists = match ($data['purpose']) {
                'banner' => Kampanye::whereKey($recordId)->exists(),
                'foto' => AnakAsuh::whereKey($recordId)->exists(),
                'gambar', 'gambar_konten' => BeritaKegiatan::whereKey($recordId)->where('terjadwal_di_kalender', false)->exists(),
            };
            abort_unless($exists, 404);
        }

        return response()->json($images->authorize($request, $data['purpose'], $recordId, $data['bytes'], $data['mime']));
    }

    public function donation(Request $request, Donasi $donasi, CloudinaryImageService $images): JsonResponse
    {
        abort_unless($donasi->allowsReceiptUpload($request->session()), 403);
        $data = $request->validate([
            'bytes' => ['required', 'integer'],
            'mime' => ['required', 'string'],
        ]);

        return response()->json($images->authorize($request, 'buktiTransfer', $donasi->id, $data['bytes'], $data['mime']));
    }

    public function receipt(Donasi $donasi, CloudinaryImageService $images, CloudflareR2Service $legacyStorage): RedirectResponse
    {
        $path = $donasi->bukti_transfer_path;
        abort_unless($path, 404);
        $url = match (true) {
            $donasi->bukti_transfer_cloudinary !== null => $images->receiptUrl($donasi->bukti_transfer_cloudinary),
            str_starts_with($path, 'images/') => asset($path),
            default => $legacyStorage->getPrivateTemporaryUrl($path),
        };
        abort_unless($url, 404);

        return redirect()->away($url, 302, ['Cache-Control' => 'private, no-store', 'Referrer-Policy' => 'no-referrer']);
    }
}
