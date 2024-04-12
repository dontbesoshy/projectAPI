<?php

namespace App\Notifications\User\BO;

use App\Http\Dto\Order\OrderDto;
use App\Models\User\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class SendOrderNotification extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(private readonly OrderDto $dto)
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
        $tableRows = '';
        foreach ($this->dto->orderItems as $product) {
            $tableRows .= "<tr><td>{$product->ean}</td><td>{$product->name}</td><td>{$product->code}</td><td>{$product->price}</td><td>{$product->pieces}</td></tr>";
        }

        $htmlTable = <<<HTML
<h1>Lista produktów</h1>
<table style="width:100%; border-collapse: collapse;" border="1">
    <tr>
        <th>EAN</th>
        <th>Nazwa</th>
        <th>Kod</th>
        <th>Cena</th>
        <th>Szt</th>
    </tr>
    $tableRows
</table>
HTML;

        return (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->greeting(
                __('email.SendOrderNotification.greeting')
            )

            ->subject(__('email.SendOrderNotification.subject'))

            ->line(__('email.SendOrderNotification.line'))

            ->line(new \Illuminate\Support\HtmlString($htmlTable))

            ->line(__('email.SendOrderNotification.fromUser', [
                'name' => User::find($this->dto->userId)->company_name
            ]))

            ->line(__('email.SendOrderNotification.totalNet', [
                'totalNet' => $this->dto->totalNet
            ]))
            ->attach(
                storage_path('app/orders/' . str_replace(' ', '_', User::find($this->dto->userId)->company_name) . '_' . now()->format('d_m_Y_H') . '.pdf')
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
