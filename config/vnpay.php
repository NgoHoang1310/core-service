<?php
return [
    'vnp_TmnCode'   => env('VNP_TMNCODE', '06104KNX'),
    'vnp_HashSecret'=> env('VNP_HASH_SECRET', 'FIO0XABBFS52ZHJHE3P284ROXQB1V5S9'),
    'vnp_Url'       => env('VNP_URL', 'https://sandbox.vnpayment.vn/paymentv2/vpcpay.html'),
    'vnp_IpnUrl' => env('VNP_IPN_URL', 'http://localhost:8081/api/payment/ipn'),
    'vnp_ReturnUrl' => env('VNP_RETURN_URL', 'http://localhost:8081/api/payment/return'),
];
