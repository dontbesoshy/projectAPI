<?php

namespace App\Services\Order;

use App\Exceptions\General\ModelNotFoundException;
use App\Http\Dto\Order\OrderDto;
use App\Http\Dto\Order\ShowOrderDto;
use App\Models\Email;
use App\Models\Order\Order;
use App\Models\User\User;
use App\Notifications\User\BO\SendOrderNotification;
use App\Resources\Order\AD\OrderCollection;
use App\Services\BasicService;
use Illuminate\Support\Facades\Storage;

class OrderService extends BasicService
{
    /**
     * Return all orders.
     *
     * @param ShowOrderDto $dto
     *
     * @return OrderCollection
     */
    public function index(ShowOrderDto $dto): OrderCollection
    {
        $user = User::find($dto->userId);

        return new OrderCollection($user->orders);
    }

    /**
     * Show order.
     *
     * @param Order $order
     * @param ShowOrderDto $dto
     *
     * @return string
     */
    public function show(Order $order, ShowOrderDto $dto)
    {
        $this->throwIf(
            $order->user_id !== $dto->userId,
            ModelNotFoundException::class
        );

        if (!Storage::disk('local')->exists($order->url)) {
            abort(404, 'File not found');
        }

        return response()->stream(function () use ($order) {
            $stream = Storage::disk('local')->readStream($order->url);
            fpassthru($stream);
            fclose($stream);
        }, 200, [
            'Content-Type' => Storage::disk('local')->mimeType($order->url),
            'Content-Disposition' => 'inline; filename="' . basename($order->url) . '"',
        ]);
    }

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

        if ($user->cart === null) {
            return;
        }

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

        $orderCount = Order::query()->where('user_id', $user->id)
            ->whereDate('created_at', now()->toDateString())
            ->count();

// Ustal numer kolejnego zamówienia
        $increment = $orderCount + 1;

        Storage::disk('local')->put(
            'orders/'.str_replace(' ', '_', $user->company_name).'_'.now()->format('d_m_Y_').$increment.'.pdf', $pdf->stream()
        );

        $order = Order::create([
            'user_id' => $user->id,
            'cart_id' => $user->cart->id,
            'comment' => substr($dto->comment, 0, 254),
            'url' => 'orders/'.str_replace(' ', '_', $user->company_name).'_'.now()->format('d_m_Y_').$increment.'.pdf',
        ]);

        Email::query()
            ->each(function (Email $email) use ($dto, $order) {
                $email->notify(new SendOrderNotification($dto, $order));
            });

        $user->cart()->delete();
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
