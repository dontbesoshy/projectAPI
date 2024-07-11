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
        $table = '<table style="width: 100%;border-collapse: collapse;font-family: Arial-Narrow;margin: 20px 0;">
                <tr>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px">LP</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px">EAN</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Nazwa towaru</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Kod produktu</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Cena</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Sztuk</th>
                </tr>';

        foreach ($dto->orderItems as $key => $product) {
            $table .= '
                <tr>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. ($key+1). '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->ean. '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 2px; font-size: 11px; font-family: DejaVu Sans !important;">'. $product->name. '</td>
                    <td style="border: 1px solid #ddd;text-align: center; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->code. '</td>
                    <td style="border: 1px solid #ddd;text-align: right; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->price. '</td>
                    <td style="border: 1px solid #ddd;text-align: right; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->pieces. '</td>
                </tr>
                ';
        }

        $table .= '</table>';

        $comment = $dto->comment ?? 'Brak';

        $pdf->loadHTML('
<div style="width: 100%; display: flex; justify-content: space-between;">
<div style="flex: 1; display: flex; justify-content: flex-end; align-items: flex-start;">
    <h3 style="margin: 0;">
      <img src="https://extremetoolsb2b.pl/images/logo.jpg" style="width: 150px; height: auto; margin-top: 20px !important;" />
    </h3>
  </div>
  <div style="flex: 1; padding-right: 10px;">
    <h3>Klient: '. $user->company_name. '</h3>
    <h5 style="font-family: DejaVu Sans !important;">Uwagi do zamówienia: ' .  $comment . '</h5>
  </div>
</div>
<div style="width: 100%; margin-top: 20px;">
 '. $table . '
</div>
');



        Storage::disk('local')->put(
            'orders/'.str_replace(' ', '_', $user->company_name).'_'.now()->format('d_m_Y_H').'.pdf', $pdf->stream()
        );

        //$allPdfs = Storage::disk('public')->allFiles('orders');

       // $pfd = Storage::get($allPdfs[0]);

        Email::query()
            ->each(function (Email $email) use ($dto) {
                $email->notify(new SendOrderNotification($dto));
            });
    }

    public function generatePdf(OrderDto $dto)
    {
        $user = User::find($dto->userId);
        $pdf = \App::make('dompdf.wrapper');
        $table = '<table style="width: 100%;border-collapse: collapse;font-family: Arial-Narrow;margin: 20px 0;">
                <tr>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px">LP</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px">EAN</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Nazwa towaru</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Kod produktu</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Cena</th>
                    <th style="border: 1px solid #ddd;text-align: left; padding: 1px;background-color: #f2f2f2;color: #333; font-size: 15px;">Sztuk</th>
                </tr>';

        foreach ($dto->orderItems as $key => $product) {
            $table .= '
                <tr>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. ($key+1). '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->ean. '</td>
                    <td style="border: 1px solid #ddd;text-align: left; padding: 2px; font-size: 11px; font-family: DejaVu Sans !important;">'. $product->name. '</td>
                    <td style="border: 1px solid #ddd;text-align: center; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->code. '</td>
                    <td style="border: 1px solid #ddd;text-align: right; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->price. '</td>
                    <td style="border: 1px solid #ddd;text-align: right; padding: 2px; font-size: 11px; font-family: Arial-Narrow;">'. $product->pieces. '</td>
                </tr>
                ';
        }

        $table .= '</table>';

        $comment = $dto->comment ?? 'Brak';

        $pdf->loadHTML('
<div style="width: 100%; display: flex; justify-content: space-between;">
<div style="flex: 1; display: flex; justify-content: flex-end; align-items: flex-start;">
    <h3 style="margin: 0;">
      <img src="https://extremetoolsb2b.pl/images/logo.jpg" style="width: 150px; height: auto; margin-top: 20px !important;" />
    </h3>
  </div>
  <div style="flex: 1; padding-right: 10px;">
    <h3>Klient: '. $user->company_name. '</h3>
    <h5 style="font-family: DejaVu Sans !important;">Uwagi do zamówienia: ' .  $comment . '</h5>
  </div>
</div>
<div style="width: 100%; margin-top: 20px;">
 '. $table . '
</div>
');

        return $pdf->stream();
    }
}
