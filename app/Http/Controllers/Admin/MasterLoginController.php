<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;

class MasterLoginController extends Controller
{
    public function login(Request $request, $company_id)
    {


        try {


            
            $company_id = base64_decode($company_id);
            $baseUrl = findBaseUrl($company_id);
            $client = new Client([
                'base_uri' => $baseUrl,
                'timeout'  => 10,
            ]);
            $params = [
                'company_id' => $company_id,
                'base_url' => $baseUrl,
            ];
            $response = $client->post('/api/master-login', [
                'form_params' => $params,
            ]);
            $body = $response->getBody()->getContents();

            $responseData = json_decode($body, true);
        } catch (RequestException $e) {
            // Handle request exceptions (e.g., network errors, HTTP errors)
            if ($e->hasResponse()) {
                // Get the HTTP response status code and message
                $statusCode = $e->getResponse()->getStatusCode();
                $reasonPhrase = $e->getResponse()->getReasonPhrase();

                // Handle the error based on the status code and reason phrase
                echo "Request failed with status code $statusCode: $reasonPhrase";
            } else {
                // Handle other types of request exceptions (e.g., connection timeout)
                echo "Request failed: " . $e->getMessage();
            }
        }
    }
}
