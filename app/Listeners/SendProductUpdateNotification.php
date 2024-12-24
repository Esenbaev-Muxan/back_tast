<?php

namespace App\Listeners;

use App\Events\ProductUpdated;
use App\Mail\ProductUpdatedMail;
use Illuminate\Support\Facades\Mail;

class SendProductUpdateNotification
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\ProductUpdated  $event
     * @return void
     */
    public function handle(ProductUpdated $event)
    {
        $product = $event->product;

        // Получаем email из конфигурации
        $recipientEmail = env('PRODUCT_UPDATE_EMAIL');

        if ($recipientEmail) {
            // Отправляем email
            Mail::to($recipientEmail)->send(new ProductUpdatedMail($product));
        }
    }
}
