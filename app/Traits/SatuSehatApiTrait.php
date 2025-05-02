<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request; // Added this line
use GuzzleHttp\Exception\ClientException;

trait SatuSehatApiTrait
{
    public $token, $url;

    public function getAccessToken()
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/x-www-form-urlencoded'
            ];
            $options = [
                'form_params' => [
                    'client_id' => auth()->user()->organization->client_id,
                    'client_secret' => auth()->user()->organization->secret_key
                ]
            ];
            $request = new Request('POST', config('app.satusehat.auth_url') . '/accesstoken?grant_type=client_credentials', $headers);
            $res = $client->sendAsync($request, $options)->wait();
            return json_decode($res->getBody()->getContents())->access_token;
        } catch (\Exception $e) {
            return null;
        }
    }

    public function postSatuSehat($url, $body)
    {
        try {
            $token = $this->getAccessToken();

            if ($token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ];
                $request = new Request('POST', config('app.satusehat.base_url') . '/' . $url, $headers, $body);
                $res = $client->sendAsync($request)->wait();
                return [
                    'status' => 'success',
                    'data' => json_decode($res->getBody()->getContents())->id
                ];
            } else {
                return [
                    'status' => 'error',
                    'data' => 'Failed get access token'
                ];
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
            return [
                'status' => 'error',
                'data' => json_decode($response->getBody()->getContents())->issue[0]?->details->text
            ];
        }
    }

    public function patchSatuSehat($url, $id, $body)
    {
        try {
            $client = new Client();
            $headers = [
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $this->token
            ];
            $request = new Request('PATCH', config('app.satusehat.base_url') . '/' . $url . '/' . $id, $headers, $body);
            $client->sendAsync($request)->wait();
            return [
                'status' => 'success',
                'data' => null
            ];
        } catch (ClientException $e) {
            $response = $e->getResponse();
            return [
                'status' => 'error',
                'data' => json_decode($response->getBody()->getContents())->issue[0]?->details->text
            ];
        }
    }

    public function putSatuSehat($url, $body)
    {
        try {
            $token = $this->getAccessToken();
            if ($token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ];
                $request = new Request('PUT', config('app.satusehat.base_url') . '/' . $url, $headers, $body);
                $client->sendAsync($request)->wait();
                return [
                    'status' => 'success',
                    'data' => null
                ];
            } else {
                return [
                    'status' => 'error',
                    'data' => 'Failed get access token'
                ];
            }
        } catch (ClientException $e) {
            $response = $e->getResponse();
            return [
                'status' => 'error',
                'data' => json_decode($response->getBody()->getContents())->issue[0]?->details->text
            ];
        }
    }

    public function getPatient()
    {
        try {
            $token = $this->getAccessToken();
            if ($token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ];
                $body = '';
                $request = new Request('GET', config('app.satusehat.base_url') . '/Patient?identifier=https://fhir.kemkes.go.id/id/nik|5271050203900001', $headers, $body);
                $res = $client->sendAsync($request)->wait();
                return json_decode($res->getBody()->getContents(), true)['entry'][0]['resource'];
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getByNikPractitioner($nik)
    {
        try {
            $this->practitioner = null;
            $token = $this->getAccessToken();
            if ($token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $token
                ];
                $request = new Request('GET', config('app.satusehat.base_url') . '/Practitioner?identifier=https://fhir.kemkes.go.id/id/nik|' . $nik, $headers, '');
                $res = $client->sendAsync($request)->wait();
                return json_decode($res->getBody()->getContents(), true);
            } else {
                return null;
            }
        } catch (\Exception $e) {
            return null;
        }
    }
}
