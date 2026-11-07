<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>Autentikasi 2FA</title>

    <!-- Fonts: Plus Jakarta Sans sesuai DESIGN_SYSTEM.md -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=plus-jakarta-sans:400,500,600,700,800" rel="stylesheet" />

    <!-- Scripts & Styles -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="min-h-screen bg-background text-foreground font-sans antialiased flex flex-col justify-between py-6 px-4 sm:py-10"
      x-data="{
          step: {{ $errors->any() ? 2 : 1 }},
          copiedSecret: false,
          copiedRecovery: false,
          downloadedTxt: false,
          otpCode: '',
          clientError: '',
          isSubmitting: false,
          secretKey: @js($secretKey),
          recoveryCodes: @js( array_values((array) $recoveryCodes) ),
          userEmail: @js( auth()->user()?->email ?? 'admin@himmatunayat.org' ),

          init() {
              document.documentElement.classList.remove('dark');
              try {
                  localStorage.removeItem('hs_theme');
              } catch (e) {}
          },

          goToStep(targetStep) {
              this.clientError = '';
              this.step = targetStep;
              if (targetStep === 2) {
                  this.$nextTick(() => {
                      if (this.$refs.codeInput) {
                          this.$refs.codeInput.focus();
                      }
                  });
              }
          },

          async copySecret() {
              if (!this.secretKey) return;
              try {
                  await navigator.clipboard.writeText(this.secretKey);
                  this.copiedSecret = true;
                  setTimeout(() => { this.copiedSecret = false; }, 2000);
              } catch (e) {
                  this.fallbackCopy(this.secretKey);
                  this.copiedSecret = true;
                  setTimeout(() => { this.copiedSecret = false; }, 2000);
              }
          },

          async copyRecoveryCodes() {
              if (!this.recoveryCodes.length) return;
              const text = this.recoveryCodes.join('\n');
              try {
                  await navigator.clipboard.writeText(text);
                  this.copiedRecovery = true;
                  setTimeout(() => { this.copiedRecovery = false; }, 2000);
              } catch (e) {
                  this.fallbackCopy(text);
                  this.copiedRecovery = true;
                  setTimeout(() => { this.copiedRecovery = false; }, 2000);
              }
          },

          fallbackCopy(text) {
              const textarea = document.createElement('textarea');
              textarea.value = text;
              textarea.style.position = 'fixed';
              textarea.style.opacity = '0';
              document.body.appendChild(textarea);
              textarea.select();
              document.execCommand('copy');
              document.body.removeChild(textarea);
          },

          downloadRecoveryTxt() {
              if (!this.recoveryCodes.length) return;
              const timestamp = new Date().toISOString().slice(0, 19).replace('T', ' ');
              const lines = [
                  '==============================================================',
                  'KODE PEMULIHAN DARURAT 2FA - YAYASAN HIMMATUN AYAT BANDUNG',
                  '==============================================================',
                  'Akun Administrator : ' + this.userEmail,
                  'Waktu Unduh        : ' + timestamp,
                  '',
                  'PENTING:',
                  '- Simpan file ini di tempat yang aman dan rahasia.',
                  '- Kode ini digunakan jika Anda kehilangan akses ke aplikasi',
                  '  authenticator (Google/Microsoft Authenticator).',
                  '- Setiap kode pemulihan hanya dapat digunakan 1 (satu) kali.',
                  '',
                  'DAFTAR KODE PEMULIHAN:',
                  ...this.recoveryCodes.map((code, idx) => (idx + 1) + '. ' + code),
                  '=============================================================='
              ];
              const blob = new Blob([lines.join('\r\n')], { type: 'text/plain;charset=utf-8' });
              const url = URL.createObjectURL(blob);
              const link = document.createElement('a');
              link.href = url;
              link.download = 'kode-pemulihan-2fa-himmatun-ayat.txt';
              document.body.appendChild(link);
              link.click();
              document.body.removeChild(link);
              URL.revokeObjectURL(url);
              this.downloadedTxt = true;
          },

          sanitizeOtpInput(event) {
              this.otpCode = (event.target.value || '').replace(/[^0-9]/g, '').slice(0, 6);
              if (this.clientError && this.otpCode.length === 6) {
                  this.clientError = '';
              }
          },

          handleVerifySubmit(event) {
              this.clientError = '';
              const cleaned = (this.otpCode || '').replace(/[^0-9]/g, '');
              if (cleaned.length !== 6) {
                  event.preventDefault();
                  this.clientError = 'Masukkan tepat 6 digit kode angka dari aplikasi authenticator Anda.';
                  if (this.$refs.codeInput) {
                      this.$refs.codeInput.focus();
                  }
                  return;
              }
              this.isSubmitting = true;
          }
      }">

    <!-- Top Utility Header -->
    <header class="mx-auto w-full max-w-lg flex items-center justify-between gap-3">
        <div class="flex items-center gap-2.5">
            <div class="flex h-10 w-10 items-center justify-center rounded-xl border border-border bg-card p-1.5 shadow-2xs">
                <img src="{{ asset('images/logo-himmatun-ayat.png') }}"
                     alt="Logo Yayasan Himmatun Ayat"
                     width="32"
                     height="32"
                     fetchpriority="high"
                     class="h-7 w-7 object-contain">
            </div>
            <div>
                <p class="text-xs font-bold text-brand-900 leading-tight">Yayasan Himmatun Ayat</p>
                <p class="text-2xs text-muted-foreground-1">Keamanan Administrator</p>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="mx-auto my-6 w-full max-w-lg">
        <div class="card">
            <!-- Card Header -->
            <div class="bg-card px-5 pt-6 pb-5 sm:px-7 sm:pt-7">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <h1 class="mt-2 text-xl sm:text-2xl font-bold text-brand-900 tracking-tight">
                            Aktivasi Autentikasi Dua Faktor
                        </h1>
                        <p class="mt-1 text-xs sm:text-sm text-muted-foreground-1 leading-relaxed">
                            Hubungkan Google Authenticator, Microsoft Authenticator, atau Authy untuk mengamankan proses login.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Card Body -->
            <div class="card-body sm:p-7 space-y-5">
                <!-- Warning Session Notice -->
                @if (session('warning'))
                    <div class="alert alert-warning" role="status">
                        <svg aria-hidden="true" class="h-4 w-4 mt-0.5 shrink-0 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <span class="text-xs leading-relaxed">{{ session('warning') }}</span>
                    </div>
                @endif

                <!-- Server Validation Error Alert -->
                @if ($errors->any())
                    <div class="alert alert-danger" role="alert">
                        <svg aria-hidden="true" class="h-4 w-4 mt-0.5 shrink-0 text-danger" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                        <div class="text-xs leading-relaxed">
                            <p class="font-semibold">Verifikasi kode gagal</p>
                            <p class="mt-0.5">{{ $errors->first() }}</p>
                        </div>
                    </div>
                @endif

                <!-- =========================================================
                     STEP 1: SCAN QR CODE & SIMPAN KODE CADANGAN (.TXT)
                     ========================================================= -->
                <div x-show="step === 1" class="space-y-5">
                    <!-- Section 1A: Scan QR Code -->
                    <section aria-labelledby="step-qr-heading" class="rounded-xl bg-muted/50 p-4 sm:p-5">
                        <div class="flex items-start gap-3">
                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-brand-100 text-brand-700 border border-brand-200">
                                <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 3.75 9.375v-4.5ZM3.75 14.625c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5a1.125 1.125 0 0 1-1.125-1.125v-4.5ZM13.5 4.875c0-.621.504-1.125 1.125-1.125h4.5c.621 0 1.125.504 1.125 1.125v4.5c0 .621-.504 1.125-1.125 1.125h-4.5A1.125 1.125 0 0 1 13.5 9.375v-4.5Z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.75v.75h-.75v-.75ZM6.75 16.5h.75v.75h-.75v-.75ZM16.5 6.75h.75v.75h-.75v-.75ZM13.5 13.5h.75v.75h-.75v-.75ZM13.5 19.5h.75v.75h-.75v-.75ZM19.5 13.5h.75v.75h-.75v-.75ZM19.5 19.5h.75v.75h-.75v-.75ZM16.5 16.5h.75v.75h-.75v-.75Z" />
                                </svg>
                            </div>
                            <div class="min-w-0 flex-1">
                                <h2 id="step-qr-heading" class="text-sm font-bold text-foreground">
                                    Langkah 1: Pindai Kode QR di Aplikasi Authenticator
                                </h2>
                            </div>
                        </div>

                        <!-- QR Code Display & Manual Setup Key -->
                        <div class="mt-4 flex flex-col sm:flex-row items-center gap-4">
                            <div class="inline-flex shrink-0 items-center justify-center rounded-xl border border-slate-200 bg-white p-3.5 shadow-2xs"
                                 role="img"
                                 aria-label="Kode QR pengaturan autentikasi dua faktor">
                                {!! $qrCodeSvg !!}
                            </div>

                            <div class="w-full min-w-0 flex-1 space-y-2 text-center sm:text-left">
                                <p class="text-xs font-semibold text-foreground">
                                    Tidak dapat memindai kamera?
                                </p>
                                <p class="text-xs text-muted-foreground-1 leading-relaxed">
                                    Masukkan kunci rahasia (Secret Key) berikut secara manual ke dalam aplikasi autentikator Anda:
                                </p>
                                <div class="flex items-center justify-between gap-2 rounded-xl border border-border bg-layer px-3 py-2">
                                    <code class="font-mono text-xs font-bold text-foreground break-all select-all tabular">{{ $secretKey }}</code>
                                    <button type="button"
                                            @click="copySecret()"
                                            class="btn btn-secondary btn-sm shrink-0 min-h-9 cursor-pointer"
                                            aria-label="Salin kunci rahasia manual">
                                        <svg x-show="!copiedSecret" aria-hidden="true" class="h-3.5 w-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125V7.875c0-.621.504-1.125 1.125-1.125H6.75a9.06 9.06 0 0 1 1.5.124m7.5 10.376h3.375c.621 0 1.125-.504 1.125-1.125V11.25c0-4.46-3.243-8.161-7.5-8.876a9.06 9.06 0 0 0-1.5-.124H9.375c-.621 0-1.125.504-1.125 1.125v3.5m7.5 10.375H9.375a1.125 1.125 0 0 1-1.125-1.125v-9.25m12 6.625v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5a3.375 3.375 0 0 0-3.375-3.375H9.75" />
                                        </svg>
                                        <svg x-show="copiedSecret" style="display: none;" aria-hidden="true" class="h-3.5 w-3.5 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                        </svg>
                                        <span x-text="copiedSecret ? 'Tersalin' : 'Salin'">Salin</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </section>

                    <!-- Section 1B: Recovery Codes & Download .txt -->
                    <section aria-labelledby="step-recovery-heading" class="rounded-xl border border-amber-200 bg-amber-50/70 p-4 sm:p-5">
                        <div class="flex flex-col sm:flex-row sm:items-start justify-between gap-3">
                            <div class="flex items-start gap-3">
                                <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-xl bg-warning-soft text-warning border border-amber-200">
                                    <svg aria-hidden="true" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 5.25a3 3 0 0 1 3 3m3 0a6 6 0 0 1-7.029 5.912c-.563-.097-1.159.026-1.563.43L10.5 17.25H8.25v2.25H6v2.25H2.25v-2.818c0-.597.237-1.17.659-1.591l6.499-6.499c.404-.404.527-1 .43-1.563A6 6 0 1 1 21.75 8.25Z" />
                                    </svg>
                                </div>
                                <div>
                                    <h2 id="step-recovery-heading" class="text-sm font-bold text-amber-950">
                                        Langkah 2: Simpan Kode Pemulihan Darurat
                                    </h2>
                                    <p class="mt-0.5 text-xs text-amber-900 leading-relaxed">
                                        Unduh atau salin kode cadangan ini ke tempat aman. Jika perangkat Anda hilang, kode ini adalah satu-satunya cara masuk kembali.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- Recovery Codes Grid -->
                        @if (count($recoveryCodes) > 0)
                            <div class="mt-3.5 grid grid-cols-2 gap-2 rounded-xl border border-amber-200 bg-layer p-3 font-mono text-xs text-foreground tabular">
                                @foreach ($recoveryCodes as $rCode)
                                    <div class="select-all rounded-lg bg-muted px-2.5 py-1.5 text-center font-semibold tracking-wide">
                                        {{ $rCode }}
                                    </div>
                                @endforeach
                            </div>

                            <!-- Action Buttons: Download .txt & Copy All -->
                            <div class="mt-3.5 flex flex-wrap items-center gap-2.5">
                                <button type="button"
                                        @click="downloadRecoveryTxt()"
                                        class="btn btn-secondary min-h-11 flex-1 sm:flex-initial cursor-pointer border-amber-300 hover:bg-amber-100/60">
                                    <svg aria-hidden="true" class="h-4 w-4 text-warning" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                                    </svg>
                                    <span x-text="downloadedTxt ? 'Unduh Ulang (.txt)' : 'Unduh Kode (.txt)'">Unduh Kode (.txt)</span>
                                </button>

                                <button type="button"
                                        @click="copyRecoveryCodes()"
                                        class="btn btn-secondary min-h-11 flex-1 sm:flex-initial cursor-pointer">
                                    <svg x-show="!copiedRecovery" aria-hidden="true" class="h-4 w-4 text-muted-foreground-2" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 7.5V6.108c0-1.135.845-2.098 1.976-2.192.373-.03.748-.057 1.123-.08M15.75 18H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08M15.75 18.75v-1.875a3.375 3.375 0 0 0-3.375-3.375h-1.5a1.125 1.125 0 0 1-1.125-1.125v-1.5A3.375 3.375 0 0 0 6.375 7.5H5.25m11.9-3.664A2.251 2.251 0 0 0 15 2.25h-1.5a2.251 2.251 0 0 0-2.15 1.586m5.8 0c.065.21.1.433.1.664v.75h-6V4.5c0-.231.035-.454.1-.664M6.75 7.5H4.875c-.621 0-1.125.504-1.125 1.125v12c0 .621.504 1.125 1.125 1.125h9.75c.621 0 1.125-.504 1.125-1.125V16.5a9 9 0 0 0-9-9Z" />
                                    </svg>
                                    <svg x-show="copiedRecovery" style="display: none;" aria-hidden="true" class="h-4 w-4 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2.25" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 12.75 6 6 9-13.5" />
                                    </svg>
                                    <span x-text="copiedRecovery ? 'Semua Kode Tersalin' : 'Salin Semua Kode'">Salin Semua Kode</span>
                                </button>
                            </div>

                            <p x-show="downloadedTxt" style="display: none;" class="mt-2.5 text-xs font-medium text-brand-800 flex items-center gap-1.5">
                                <svg aria-hidden="true" class="h-4 w-4 shrink-0 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span>File <code class="font-mono">kode-pemulihan-2fa-himmatun-ayat.txt</code> berhasil diunduh.</span>
                            </p>
                        @else
                            <div class="mt-3 rounded-xl border border-amber-200 bg-layer p-3.5 text-xs text-muted-foreground-1">
                                Kode pemulihan belum tersedia. Muat ulang halaman ini untuk membuat ulang kode pemulihan darurat.
                            </div>
                        @endif
                    </section>

                    <!-- Step 1 Primary Navigation Button -->
                    <div class="pt-1">
                        <button type="button"
                                @click="goToStep(2)"
                                class="btn btn-primary w-full min-h-11 py-3 cursor-pointer">
                            <span>Lanjutkan ke Verifikasi Kode</span>
                        </button>
                    </div>
                </div>

                <!-- =========================================================
                     STEP 2: MASUKKAN 6-DIGIT KODE VERIFIKASI
                     ========================================================= -->
                <div x-show="step === 2" style="display: none;" class="space-y-5">
                    <!-- Summary Banner from Step 1 -->
                    <div class="flex items-center justify-between gap-3 rounded-xl border border-brand-200 bg-brand-50/70 px-4 py-3">
                        <div class="flex items-center gap-2.5 min-w-0">
                            <svg aria-hidden="true" class="h-5 w-5 shrink-0 text-brand-700" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75m-3-7.036A11.959 11.959 0 0 1 3.598 6 11.99 11.99 0 0 0 3 9.749c0 5.592 3.824 10.29 9 11.623 5.176-1.332 9-6.03 9-11.622 0-1.31-.21-2.571-.598-3.751h-.152c-3.196 0-6.1-1.248-8.25-3.285Z" />
                            </svg>
                            <div class="min-w-0">
                                <p class="text-xs font-bold text-brand-900">Sudah memindai QR & menyimpan kode cadangan?</p>
                                <p class="text-2xs text-muted-foreground-1 truncate">Masukkan kode 6 digit yang tampil di aplikasi autentikator Anda.</p>
                            </div>
                        </div>
                        <button type="button"
                                @click="goToStep(1)"
                                class="btn btn-secondary btn-sm shrink-0 min-h-9 cursor-pointer">
                            Lihat QR
                        </button>
                    </div>

                    <!-- Form Confirm 2FA -->
                    <form method="POST"
                          action="{{ route('admin.two-factor-setup.confirm') }}"
                          @submit="handleVerifySubmit($event)"
                          class="space-y-5">
                        @csrf

                        <div>
                            <label for="code" class="form-label">
                                Masukkan 6 Digit Kode Autentikator
                            </label>
                            <input
                                x-ref="codeInput"
                                type="text"
                                id="code"
                                name="code"
                                x-model="otpCode"
                                @input="sanitizeOtpInput($event)"
                                inputmode="numeric"
                                pattern="[0-9]{6}"
                                maxlength="6"
                                required
                                autocomplete="one-time-code"
                                placeholder="000000"
                                aria-describedby="code-hint"
                                class="form-input text-center font-mono text-2xl font-bold tracking-[0.35em] py-3.5 tabular {{ $errors->any() ? 'border-danger focus:border-danger focus:ring-danger/20' : '' }}"
                            >
                            <p id="code-hint" class="form-hint text-center">
                                Kode berganti otomatis setiap 30 detik di aplikasi autentikator Anda.
                            </p>
                            <p x-show="clientError"
                               style="display: none;"
                               x-text="clientError"
                               role="alert"
                               class="form-error text-center"></p>
                        </div>

                        <div class="flex flex-col-reverse sm:flex-row items-center gap-2.5 pt-1">
                            <button type="button"
                                    @click="goToStep(1)"
                                    :disabled="isSubmitting"
                                    class="btn btn-secondary w-full sm:w-auto min-h-11 px-4 py-3 cursor-pointer disabled:cursor-not-allowed">
                                <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                                </svg>
                                <span>Kembali</span>
                            </button>

                            <button type="submit"
                                    :disabled="isSubmitting"
                                    class="btn btn-primary w-full flex-1 min-h-11 py-3 cursor-pointer disabled:cursor-not-allowed">
                                <svg x-show="!isSubmitting" aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                </svg>
                                <span x-text="isSubmitting ? 'Memverifikasi & Mengaktifkan...' : 'Konfirmasi dan Aktifkan 2FA'">
                                    Konfirmasi dan Aktifkan 2FA
                                </span>
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Card Footer: Logout Action -->
            <div class="card-footer flex items-center justify-between gap-3">
                <span class="text-xs text-muted-foreground-1 truncate">
                    Masuk sebagai <strong class="font-semibold text-foreground">{{ auth()->user()?->email ?? 'Administrator' }}</strong>
                </span>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="btn btn-ghost btn-sm min-h-9 text-muted-foreground-2 hover:text-danger cursor-pointer">
                        <svg aria-hidden="true" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke-width="1.75" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6a2.25 2.25 0 0 0-2.25 2.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                        </svg>
                        <span>Keluar dari akun</span>
                    </button>
                </form>
            </div>
        </div>
    </main>

    <!-- Page Footer -->
    <footer class="mx-auto w-full max-w-lg text-center text-xs text-muted-foreground-1 space-y-0.5">
        <p>&copy; {{ date('Y') }} Yayasan Himmatun Ayat Bandung.</p>
        <p class="text-2xs text-muted-foreground-1">Sistem Informasi Pengelolaan Yayasan Terpadu</p>
    </footer>

</body>
</html>
