<?php

namespace App\Notifications;

use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailToUserWithTokenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $user;

    /**
     * Create a new notification instance.
     */
    public function __construct(User $user)
    {
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database', 'mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->greeting('Witaj, ' . $this->user->name . '!')
            ->subject('Dziękujemy za rejestrację w serwisie')
            ->line('Dziękujemy za rejestrację w serwisie. Kliknij w poniższy link, aby zweryfikować swój adres e-mail.')
            ->action('Zweryfikuj', url('/123'))
            ->line('Dziękujemy! ');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
