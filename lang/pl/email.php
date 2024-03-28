<?php

return [
    'main' => [
        'below' => 'Jeśli masz problem z kliknięciem przycisku "Zweryfikuj", skopiuj i wklej poniższy adres URL do swojej przeglądarki internetowej:',
        'regards' => 'Z pozdrowieniami,',
    ],
    'SendEmailToUserWithTokenNotification' => [
        'greeting' => 'Witaj, :name!',
        'subject' => config('app.name') . ': Aktywuj swoje konto',
        'line' => 'Dziękujemy za rejestrację w serwisie. Kliknij w poniższy przycisk, aby zweryfikować swój adres e-mail.',
        'buttonAction' => 'Zweryfikuj',
    ],
    'SendEmailToUserWithNewPasswordNotification' => [
        'greeting' => 'Witaj, :name!',
        'subject' => config('app.name') . ': Nowe hasło',
        'line' => 'Twoje nowe hasło do logowania: :newPassword',
    ],

    'SendOrderNotification' => [
        'greeting' => 'Cześć!',
        'subject' => config('app.name') . ': Nowe zamówienie',
        'line' => 'Nowe zamówienie:',
        'fromUser' => 'Od kontrahenta: :name',
        'totalNet' => 'Suma netto: :totalNet zł',
    ],
];
