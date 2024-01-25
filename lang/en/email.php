<?php

return [
    'main' => [
        'below' => 'If you have trouble clicking the "Verify" button, copy and paste the URL below into your web browser:',
        'regards' => 'With greetings,',
    ],
    'SendEmailToUserWithTokenNotification' => [
        'greeting' => 'Hello, :name!',
        'subject' => config('app.name') . ': Activate your account',
        'line' => 'Thank you for registering for the service. Click on the button below to verify your email address.',
        'buttonAction' => 'Verify',
    ],
];
