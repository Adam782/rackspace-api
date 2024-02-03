<?php

namespace App\Http\Controllers;

use App\Goutte\Client;
use Symfony\Component\HttpClient\HttpClient;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use DB;
use Session;
use Illuminate\Support\Facades\Response;
use Helper;
use File;
class ApiController extends Controller
{
    public function __construct()
    {
       // $this->middleware('auth:admin');
    }

    

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    
     public function index(){
        //Get Token Id And tenant
            $url = 'https://identity.api.rackspacecloud.com/v2.0/tokens';
            $data = json_encode(array(
                "auth" => array(
                    "RAX-KSKEY:apiKeyCredentials" => array(
                        "username" => "",
                        "apiKey" => ""
                    )
                )
            ));

            $ch = curl_init($url);
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data)
            ));

            $response = curl_exec($ch);
            curl_close($ch);

            $result = json_decode($response, true);
           // echo "<pre>"; print_r($result); echo "</pre>"; die;
            //Get Token Id And tenant

            $token = $result['access']['token']['id'];
            $server_url = $result['access']['serviceCatalog'][0]['endpoints'][0]['publicURL'] . '/teasers'; 
            $ch = curl_init($server_url);
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('X-Auth-Token: ' . $token, 'Accept: application/json'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

            $server_response = curl_exec($ch);
            curl_close($ch);

            $server_data = json_decode($server_response, true);
            
            //Get Conatiner File Details
            echo "<pre>"; print_r($server_data); echo "</pre>"; die;
     }

}