<?php

namespace App\Class;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Log;

class SatusehatClass
{
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        //
    }

    public static function getAccessToken()
    {
        $client = new Client();
        $headers = [
            'Content-Type' => 'application/x-www-form-urlencoded'
        ];
        $options = [
            'form_params' => [
                'client_id' => config('app.satusehat.client_key'),
                'client_secret' => config('app.satusehat.secret_key')
            ]
        ];
        $request = new Request('POST', config('app.satusehat.auth_url') . '/accesstoken?grant_type=client_credentials', $headers);
        $res = $client->sendAsync($request, $options)->wait();
        return json_decode($res->getBody()->getContents())->access_token;
    }

    public static function getPatientByNik($nik)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::getAccessToken()
            ];
            $body = '';
            $request = new Request('GET', config('app.satusehat.base_url') . '/Patient?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik, $headers, $body);
            $res = $client->sendAsync($request)->wait();
            return json_decode($res->getBody()->getContents(), true)['entry'][0]['resource'];
        } catch (\Exception $e) {
            Log::error('Error fetching practitioner data: ' . $e->getMessage());
            return null;
        }
    }

    public static function getPractitionerByNik($nik)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::getAccessToken()
            ];
            $request = new Request('GET', config('app.satusehat.base_url') . '/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik, $headers, '');
            $res = $client->sendAsync($request)->wait();
            return json_decode($res->getBody()->getContents(), true);
        } catch (\Exception $e) {
            Log::error('Error fetching practitioner data: ' . $e->getMessage());
            return null;
        }
    }

    public function postSatuSehat($url, $body)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . self::getAccessToken()
            ];
            $request = new Request('POST', config('app.satusehat.base_url') . '/' . $url, $headers, $body);
            $res = $client->sendAsync($request)->wait();
            return json_decode($res->getBody()->getContents(), true)->id;
        } catch (\Exception $e) {
            Log::error('Error post data: ' . $e->getMessage());
            return null;
        }
    }
}
