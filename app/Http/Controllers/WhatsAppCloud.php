<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Twilio\Rest\Client;
use App\Admin;
use App\Language;
class WhatsAppCloud extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private $Token;

    /**
     * 
     * Generacion de Constructor
     * 
     */
    public function __construct()
    {
        $this->Token = "EABOJYJMwOvoBAO3ZABWNb1Xk0ZBUkK29OrChIKYRjXggtjiTygabRTfJLIKnvIAvlmwRX4DjfyFC0f5wUGYeVTIIxwKNI8LykSNI4IHPF80vSfKiMgY5PWe8h5AplZABTFSfKk62CMfGlHxQ474rtDTn9ZBD42H8DZCQUSqZCQNcyAjCLVCeLZAwdJ8GS2ZCIYKL61LZCQtR9N9xayLhha97HWz5nBFTEUfMZD";
    }

    // Enviamos MSG
    function SendMessage()
	{
		$fields = array(
            'messaging_product' => 'whatsapp',
            'to'    => '526361229546',// 528121067435 / 526361229546
            'type'  => 'template',
            'template' => array(
                'name' => 'hello_world',
                'language' => array(
                    'code' => 'es_MX'
                )
            )
        );
 
        return $this->CurlGet($fields,"https://graph.facebook.com/v13.0/105545148849246/messages/");
	
    
    }


    function CurlGet($fields,$url)
    {
        $fields = json_encode($fields);

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url); 
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json',
		'Authorization: Bearer '.$this->Token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        $req = json_decode($response,true);

        return $req;
    }

}