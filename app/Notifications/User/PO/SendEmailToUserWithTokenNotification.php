<?php

namespace App\Notifications\User\PO;

use App\Models\User\RegisterToken;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendEmailToUserWithTokenNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly User $user, private readonly RegisterToken $registerToken)
    {
    }

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
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->greeting(
                __('email.SendEmailToUserWithTokenNotification.greeting', ['name' => $this->user->name])
            )

            ->subject(__('email.SendEmailToUserWithTokenNotification.subject'))

            ->line(__('email.SendEmailToUserWithTokenNotification.line'))

            ->action(
                __('email.SendEmailToUserWithTokenNotification.buttonAction'),
                route('verifyToken', ['token' => $this->registerToken->token])
            );
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
