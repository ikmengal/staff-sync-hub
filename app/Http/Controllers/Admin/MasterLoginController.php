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
            if (isset($responseData['success']) && !empty($responseData['success'])) {
                if (isset($responseData['data']['url']) && !empty($responseData['data']['url'])) {
                    return redirect()->to($responseData['data']['url']);
                }
            } else {
                return redirect()->route("dashboard")->with("error", "Failed to login");
            }
        } catch (RequestException $e) {

            if ($e->hasResponse()) {
                $statusCode = $e->getResponse()->getStatusCode();
                $reasonPhrase = $e->getResponse()->getReasonPhrase();
                echo "Request failed with status code $statusCode: $reasonPhrase";
            } else {
                echo "Request failed: " . $e->getMessage();
            }
        }
    }
}
