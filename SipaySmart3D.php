<?php

class SipaySmart3D
{

    /**
     * Uğurcan Yaş
     *  16.07.2024
     * Altf4 Yazılım Software Architect & Developer
     * QNB Finansbank Payment Gateway Integration
     */
    private $end_point = "https://portal.qnbpay.com.tr/ccpayment/api/paySmart3D";

    private $merchant_key = '';

    private $app_id = "";
    private $app_secret = "";

    private $return_url = "https://www.altf4.com.tr/test";
    private $cancel_url = "https://www.altf4.com.tr/test2";


    public function paySmart3D($request)
    {

        $invoice_id = rand(100, 99999);
        $currency_code = "TRY";
        $total = 0;

        $cart = [
            [
                'name' => "Test Product",
                'price' => 10,
                'quantity' => 1,
                'description' => "Some Description",
            ]
        ];

        foreach ($cart as $item) {
            $productPrice = $item['price'] * $item['quantity'];
            $total = $total + $productPrice;
        }

        $item_js = json_encode($cart);

        $name = "Customer Name";
        $surname = "Customer surname";
        $sale_web_hook = '';

        if (
            !isset($request['installments_number'])
            || (isset($request['installments_number']) && $request['installments_number'] < 1)
        ) {
            $installment = 1;
        } else {
            $installment = $request['installments_number'];
        }

        $hash_key = $this->generateHashKey(
            $total,
            $installment,
            $currency_code,
            $this->merchant_key,
            $invoice_id,
            $this->app_secret
        );

        $invoice = [
            'merchant_key' => $this->merchant_key,
            'invoice_id' => $invoice_id,
            'total' => $total,
            'items' => $item_js,
            'currency_code' => $currency_code,
            'cc_holder_name' => $request['cc_holder_name'],
            'cc_no' =>  $request['cc_no'],
            'expiry_month' => $request['expiry_month'],
            'expiry_year' => $request['expiry_year'],
            'cvv' =>  $request['cvv'],
            'installments_number' => $installment,
            'cancel_url' => $this->cancel_url,
            'return_url' => $this->return_url,
            'hash_key' => $hash_key,
            'name' => $name,
            'surname' => $surname,
        ];


        $invoice['bill_address1'] = 'Address 1 should not more than 100';
        $invoice['bill_address2'] = 'Address 2';
        $invoice['bill_city'] = 'Istanbul';
        $invoice['bill_postcode'] = '1111';
        $invoice['bill_state'] = 'Istanbul';
        $invoice['bill_country'] = 'TURKEY';
        $invoice['bill_phone'] = '008801777711111';
        $invoice['bill_email'] = 'demo@sipay.com.tr';
        $invoice['sale_web_hook_key'] = $sale_web_hook;

        if (isset($request['order_type'])) {
            $invoice['order_type'] = 1;
            $invoice['recurring_payment_number'] = $request['recurring_payment_number'];
            $invoice['recurring_payment_cycle'] = $request['recurring_payment_cycle'];
            $invoice['recurring_payment_interval'] = $request['recurring_payment_interval'];
            $invoice['recurring_web_hook_key'] = $request['recurring_web_hook_key'] ?? '';
        }

        $formOpen = "<form action='{$this->end_point}/ccpayment/api/paySmart3D' method='post' id='the-form'>";
        $form = '';
        foreach ($invoice as $key => $value) {
            $form .= "<input type='hidden' name='{$key}' value='{$value}'>";
        }

        $formClose = "</form>";
        $script = '<script type="text/javascript">window.onload = function(){
                        document.getElementById("the-form").submit();}
                  </script>';

        echo $formOpen . $form . $formClose . $script;
        exit;
    }

    private function generateHashKey($total, $installment, $currency_code, $merchant_key,  $invoice_id, $app_secret)
    {

        $data = $total . '|' . $installment . '|' . $currency_code . '|' . $merchant_key . '|' . $invoice_id;

        $iv = substr(sha1(mt_rand()), 0, 16);
        $password = sha1($app_secret);

        $salt = substr(sha1(mt_rand()), 0, 4);
        $saltWithPassword = hash('sha256', $password . $salt);

        $encrypted = openssl_encrypt(
            "$data",
            'aes-256-cbc',
            "$saltWithPassword",
            null,
            $iv
        );
        $msg_encrypted_bundle = "$iv:$salt:$encrypted";
        $msg_encrypted_bundle = str_replace('/', '__', $msg_encrypted_bundle);
        return $msg_encrypted_bundle;
    }
}
