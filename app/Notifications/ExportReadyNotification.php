<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ExportReadyNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $downloadUrl;
    protected $message;

    public function __construct($downloadUrl, $message = 'Your translation export is ready for download.')
    {
        $this->downloadUrl = $downloadUrl;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $mailMessage = (new MailMessage)
            ->subject('Translation Export Status')
            ->line($this->message);

        if ($this->downloadUrl) {
            $mailMessage->action('Download Export', $this->downloadUrl);
        }

        return $mailMessage;
    }
}
