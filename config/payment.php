<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Driver
    |--------------------------------------------------------------------------
    |
    | This value determines which of the following gateway to use.
    | You can switch to a different driver at runtime.
    |
    */
    'default' => 'paypingWallet',

    /*
    |--------------------------------------------------------------------------
    | List of Drivers
    |--------------------------------------------------------------------------
    |
    | These are the list of drivers to use for this package.
    | You can change the name. Then you'll have to change
    | it in the map array too.
    |
    */
    'drivers' => [
        'zarinpalWallet' => [
            /* normal api */
            'apiPurchaseUrl' => 'https://api.zarinpal.com/pg/v4/payment/request.json',
            'apiPaymentUrl' => 'https://www.zarinpal.com/pg/StartPay/',
            'apiVerificationUrl' => 'https://api.zarinpal.com/pg/v4/payment/verify.json',

            /* sandbox api */
            'sandboxApiPurchaseUrl' => 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl',
            'sandboxApiPaymentUrl' => 'https://sandbox.zarinpal.com/pg/StartPay/',
            'sandboxApiVerificationUrl' => 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl',

            /* zarinGate api */
            'zaringateApiPurchaseUrl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',
            'zaringateApiPaymentUrl' => 'https://www.zarinpal.com/pg/StartPay/:authority/ZarinGate',
            'zaringateApiVerificationUrl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',

            'mode' => 'normal', // can be normal, sandbox, zaringate
            'merchantId' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
            'callbackUrl' => config('app.front_url') . '/wallet-charge-callback-zarinpal',
            'description' => 'payment using zarinpal',
        ],
        'zarinpalInvoice' => [
            /* normal api */
            'apiPurchaseUrl' => 'https://api.zarinpal.com/pg/v4/payment/request.json',
            'apiPaymentUrl' => 'https://www.zarinpal.com/pg/StartPay/',
            'apiVerificationUrl' => 'https://api.zarinpal.com/pg/v4/payment/verify.json',

            /* sandbox api */
            'sandboxApiPurchaseUrl' => 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl',
            'sandboxApiPaymentUrl' => 'https://sandbox.zarinpal.com/pg/StartPay/',
            'sandboxApiVerificationUrl' => 'https://sandbox.zarinpal.com/pg/services/WebGate/wsdl',

            /* zarinGate api */
            'zaringateApiPurchaseUrl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',
            'zaringateApiPaymentUrl' => 'https://www.zarinpal.com/pg/StartPay/:authority/ZarinGate',
            'zaringateApiVerificationUrl' => 'https://ir.zarinpal.com/pg/services/WebGate/wsdl',

            'mode' => 'normal', // can be normal, sandbox, zaringate
            'merchantId' => 'XXXXXXXX-XXXX-XXXX-XXXX-XXXXXXXXXXXX',
            'callbackUrl' => config('app.front_url') . '/invoice-callback-zarinpal',
            'description' => 'payment using zarinpal',
        ],
        'paypingWallet' => [
            'apiPurchaseUrl' => 'https://api.payping.ir/v2/pay/',
            'apiPaymentUrl' => 'https://api.payping.ir/v2/pay/gotoipg/',
            'apiVerificationUrl' => 'https://api.payping.ir/v2/pay/verify/',
            'merchantId' => 'YwkYNE780gkk9ccA8ax8vKTRrWZTDQdBgOKS1WH7DBE',
            'callbackUrl' => config('app.app_url') . '/wallet-charge-callback-paypin',
            'description' => 'payment using payping',
            'currency' => 'R', //Can be R, T (Rial, Toman)
        ],
        'paypingInvoice' => [
            'apiPurchaseUrl' => 'https://api.payping.ir/v2/pay/',
            'apiPaymentUrl' => 'https://api.payping.ir/v2/pay/gotoipg/',
            'apiVerificationUrl' => 'https://api.payping.ir/v2/pay/verify/',
            'merchantId' => 'YwkYNE780gkk9ccA8ax8vKTRrWZTDQdBgOKS1WH7DBE',
            'callbackUrl' => config('app.app_url') . '/invoice-callback-paypin',
            'description' => 'payment using payping',
            'currency' => 'R', //Can be R, T (Rial, Toman)
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Maps
    |--------------------------------------------------------------------------
    |
    | This is the array of Classes that maps to Drivers above.
    | You can create your own driver if you like and add the
    | config in the drivers array and the class to use for
    | here with the same name. You will have to extend
    | App\Services\Payment\Abstracts\Driver in your driver.
    |
    */
    'map' => [
//        'zarinpalWallet' => \App\Services\Payment\Drivers\Zarinpal\Zarinpal::class,
//        'zarinpalInvoice' => \App\Services\Payment\Drivers\Zarinpal\Zarinpal::class,
        'paypingWallet' => \App\Services\Payment\Drivers\Payping\Payping::class,
        'paypingInvoice' => \App\Services\Payment\Drivers\Payping\Payping::class,
    ]
];
