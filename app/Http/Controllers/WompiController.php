<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
class WompiController extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $public_key;
    private $private_key;
    private $endpoint = "https://production.wompi.co";

    public function __construct()
    {
        $this->public_key = "pub_prod_lGYxHGF1deVzub3xFeFqx4UzemCJdgsj";
        $this->private_key = "prv_prod_nGfgjkadFepIDxxVVozm9UEHyGbKD898";
    }

    // Generamos un Token de aceptacion
    public function GenerateAcceptanceToken()
    {
        return $this->CurlRequest([],"GET","/v1/merchants/".$this->public_key);
    }

    // Generamos un token de tarjeta
    public function GenerateTokenCard($data)
    {
        return $this->CurlRequest($data,"POST","/v1/tokens/cards");
    }

    // Generate Charge Cliente  
    function CreateTransactions($data)
    {
        $fields = array(
            "acceptance_token" => $data['acceptance_token'],
            "amount_in_cents" => intval($data['amount_in_cents']),
            "currency" => "COP",
            "customer_email" => $data['customer_email'],
            "payment_method" => array(
                "type" => "CARD",
                "token" => $data['payment_method']['token'],
                "installments" => 1
            ),
            "redirect_url" => "https://www.zapplogistica.com/payment-prod/confirm_payment.php",
            "reference" => $data['reference'],
            "customer_data" => array(
                "phone_number" => $data['customer_data']['phone_number'],
                "full_name" => $data['customer_data']['full_name'],
            )
        );

        return $this->CurlRequest($fields,"POST","/v1/transactions/");
    }

    /**
     * Request de CURL
     */
    function CurlRequest($fields,$type,$url)
    {
        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $this->endpoint.$url);

        if ($type == 'POST') {
            $fields = json_encode($fields);

            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		    'Authorization: Bearer '.$this->public_key));
            curl_setopt($ch, CURLOPT_POST, TRUE);
            curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
		}


		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_HEADER, FALSE);		
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
     

        $response = curl_exec($ch);
        curl_close($ch);

        $req = json_decode($response,true);

        return $req;
    }

}