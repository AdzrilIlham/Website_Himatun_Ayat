<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class EmailOtpNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     * Note: We do not log OTP values.
     */
    public function __construct(
        public readonly string $otpCode,
        public readonly string $purposeDescription = 'verifikasi perangkat'
    ) {}

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Kode Keamanan OTP — Yayasan Himmatun Ayat')
            ->greeting('Assalamu’alaikum, '.$notifiable->name)
            ->line('Anda menerima email ini karena ada permintaan '.$this->purposeDescription.' pada akun Administrator Anda.')
            ->line('Gunakan kode 6-digit berikut untuk melanjutkan proses:')
            ->line('# '.$this->otpCode)
            ->line('Kode ini hanya berlaku selama 5 menit dan memiliki batas 5 kali percobaan.')
            ->line('Jika Anda tidak melakukan permintaan ini, segera amankan akun Anda.')
            ->salutation('Wassalamu’alaikum, Yayasan Himmatun Ayat Bandung');
    }
}
