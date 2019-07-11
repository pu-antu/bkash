<?php

namespace Pstw\Bkash;

class Bkash
{

    /**
     * Bkash API configuration.
     *
     * @var array
     */
    private $config;

    /**
     * Bkash API Url.
     *
     * @var string
     */
    private $apiUrl;

    /**
     * Bkash API accesstoken.
     *
     * @var string
     */
    private $accesstoken;


    /**
     * Bkash header.
     *
     * @var string
     */
    private $header;


    /**
     * Bkash API posttoken.
     *
     * @var array
     */

    private $posttoken;

    private $status = 'sandbox';


    public function __construct()
    {
        // Setting Bkash API Credentials
        $this->setApiCredentials(config('bkash'));

    }

    /**
     * Set API Credentialsl.
     *
     * @param array $credentials
     *
     * @return void
     */

    private function setApiCredentials($credentials)
    {

        if ($credentials['mode'] === 'sandbox') {
            $this->apiUrl = 'https://checkout.sandbox.bka.sh/v1.0.0-beta/checkout/';
            $this->status = 'sandbox';

        } else {
            $this->apiUrl = 'https://checkout.pay.bka.sh/v1.2.0-beta/checkout/';
            $this->status = 'live';
            //$this->apiUrl = 'https://checkout.bka.sh/v1.0.0-beta/checkout/';
        }
        $this->config = $credentials;
    }

    ///make HTTP api request.
    private function makeRequest($action,$end_url,$args = array())
    {
        if (!function_exists('curl_init') || !function_exists('curl_setopt')) {
            return '';
        }
        $url = $this->apiUrl . $end_url;
        switch ($action){

            case 'access_token':
                $this->header = array(
                    'Content-Type:application/json',
                    'password:' . $this->config[$this->status]['password'],
                    'username:' . $this->config[$this->status]['username'],
                );
                $this->posttoken = array(
                    'app_key' => $this->config[$this->status]['app_key'],
                    'app_secret' => $this->config[$this->status]['app_secret'],
                );
                $this->posttoken = json_encode($this->posttoken);
                break;

            case 'create_payment':
                $this->header = array(
                    'Content-Type:application/json',
                    'authorization:'.$this->accesstoken,
                    'x-app-key:'.$this->config[$this->status]['app_key'],
                );
                $this->posttoken = $args;
                $this->posttoken = json_encode($this->posttoken);
                break;

            case 'execute_payment':
                $this->header = array(
                    'Content-Type:application/json',
                    'authorization:'.$this->accesstoken,
                    'x-app-key:'.$this->config[$this->status]['app_key'],
                );
                break;
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        if($action != 'execute_payment'){
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->posttoken);
        }
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        $resultdata = curl_exec($ch);
        if (curl_error($ch)) {
            return curl_error($ch);
        }
        curl_close($ch);
        return json_decode($resultdata, true);
    }

    public function bkas_init()
    {
        return $this;
    }

    public function get_access_token()
    {
        return $this->makeRequest('access_token','token/grant');
    }

    public function create_payment($access_token, $amount, $invoice_no, $currency = 'BDT', $intent = 'sale')
    {
        $data =array(
            'amount'    =>  $amount,
            'merchantInvoiceNumber' =>  $invoice_no,
            'currency'  =>  $currency,
            'intent'    =>  $intent,
        );

        $this->accesstoken = $access_token['id_token'];
        return $this->makeRequest('create_payment','payment/create', $data);
    }

    public function execute_payment($access_token,$payment_id)
    {
        $this->accesstoken = $access_token;
        return $this->makeRequest('execute_payment','payment/execute/'.$payment_id);
    }

}
