@extends('layouts.admin')

@section('title', 'Edit Administrator — Yayasan Himmatun Ayat')

@section('content')
<div class="max-w-2xl space-y-6 pb-12">
    <div>
        <a href="{{ route('admin.users.index') }}" class="text-xs font-semibold text-brand-700 hover:underline">&larr; Kembali ke daftar administrator</a>
        <h1 class="mt-3 text-xl sm:text-2xl font-bold text-foreground">Edit Administrator</h1>
        <p class="mt-1 text-xs sm:text-sm text-muted-foreground">Perubahan email akan meminta verifikasi email dan aktivasi 2FA ulang.</p>
    </div>

    @if ($errors->any())
        <div class="rounded-xl border border-danger/30 bg-danger-soft px-4 py-3 text-sm text-danger" role="alert">
            <ul class="list-inside list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="{{ route('admin.users.update', $admin) }}" class="space-y-4 rounded-2xl border border-border bg-card p-5 sm:p-6 shadow-xs">
        @csrf
        @method('PUT')

        <div>
            <label for="name" class="mb-1 block text-xs font-semibold text-foreground">Nama Lengkap</label>
            <input id="name" name="name" type="text" value="{{ old('name', $admin->name) }}" required maxlength="255" autocomplete="name" class="w-full rounded-xl border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:outline-hidden focus:ring-2 focus:ring-brand-700/20">
        </div>

        <div>
            <label for="email" class="mb-1 block text-xs font-semibold text-foreground">Alamat Email</label>
            <input id="email" name="email" type="email" value="{{ old('email', $admin->email) }}" required maxlength="255" autocomplete="email" class="w-full rounded-xl border border-border bg-background px-3.5 py-2.5 text-sm text-foreground focus:border-brand-700 focus:outline-hidden focus:ring-2 focus:ring-brand-700/20">
        </div>

        <div class="flex justify-end gap-2 pt-2">
            <a href="{{ route('admin.users.index') }}" class="rounded-xl border border-border px-4 py-2 text-sm font-semibold text-muted-foreground-2 hover:bg-muted">Batal</a>
            <button type="submit" class="btn btn-primary px-4 py-2 text-sm font-semibold">Simpan Perubahan</button>
        </div>
    </form>
</div>
@endsection
