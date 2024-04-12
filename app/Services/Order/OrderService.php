<?php

namespace App\Services\Order;

use App\Http\Dto\Order\OrderDto;
use App\Models\Email;
use App\Models\User\User;
use App\Notifications\User\BO\SendOrderNotification;
use App\Services\BasicService;
use Illuminate\Support\Facades\Storage;

class OrderService extends BasicService
{
    /**
     * Send order.
     *
     * @param OrderDto $dto
     *
     * @return void
     */
    public function store(OrderDto $dto)
    {
        $user = User::find($dto->userId);
        $pdf = \App::make('dompdf.wrapper');
        $table = '<table style="width: 100%;border-collapse: collapse;font-family: Arial, sans-serif;margin: 20px 0;">
                <tr>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 8px;background-color: #f2f2f2;color: #333;">EAN</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 8px;background-color: #f2f2f2;color: #333;">Nazwa towaru</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 8px;background-color: #f2f2f2;color: #333;">Kod produktu</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 8px;background-color: #f2f2f2;color: #333;">Cena</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 8px;background-color: #f2f2f2;color: #333;">Sztuk</th>
                </tr>';

        foreach ($dto->orderItems as $product) {
            $table .= '
                <tr>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 8px;">'. $product->ean. '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 8px;">'. $product->name. '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 8px;">'. $product->code. '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 8px;">'. $product->price. '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 8px;">'. $product->pieces. '</td>
                </tr>
                ';
        }

        $table .= '</table>';

        $pdf->loadHTML('
<h3>Od firmy: '. $user->company_name. '</h3>
<h4>Suma netto: '. $dto->totalNet. ' PLN</h4>
<h5>Produkty:</h5>'. $table. '
');

        Storage::disk('local')->put(
            'orders/'.str_replace(' ', '_', $user->company_name).'_'.now()->format('d_m_Y_H_i').'.pdf', $pdf->stream()
        );

        //$allPdfs = Storage::disk('public')->allFiles('orders');

       // $pfd = Storage::get($allPdfs[0]);

        Email::query()
            ->each(function (Email $email) use ($dto) {
                $email->notify(new SendOrderNotification($dto));
            });
    }
}
