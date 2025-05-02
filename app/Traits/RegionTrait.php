<?php

namespace App\Traits;

use GuzzleHttp\Client;
use GuzzleHttp\Psr7\Request;

trait RegionTrait
{
    use SatuSehatApiTrait;

    public $provinceData = [], $cityData = [], $districtData = [], $subDistrictData = [];

    public function bootRegionTrait()
    {
        $this->getProvinceData();
    }

    public function getProvinceData()
    {
        try {
            $this->token = $this->getAccessToken();
            if ($this->token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ];
                $body = '';
                $request = new Request('GET', config('app.satusehat.url') . '/masterdata/v1/provinces?codes', $headers, $body);
                $res = $client->sendAsync($request)->wait();
                $this->provinceData = json_decode($res->getBody()->getContents(), true)['data'];
            } else {
                $this->provinceData = [];
            }
        } catch (\Exception $e) {
            $this->provinceData = [];
        }
    }

    public function getCityData($province)
    {
        try {
            $this->region_city_code = null;
            if ($this->token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ];
                $body = '';
                $request = new Request('GET', config('app.satusehat.url') . '/masterdata/v1/cities?province_codes=' . $province, $headers, $body);
                $res = $client->sendAsync($request)->wait();
                $this->cityData = json_decode($res->getBody()->getContents(), true)['data'];
            } else {
                $this->cityData = [];
            }
        } catch (\Exception $e) {
            $this->cityData = [];
        }
    }

    public function getDistrictData($city)
    {
        try {
            $this->region_district_code = null;
            if ($this->token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ];
                $body = '';
                $request = new Request('GET', config('app.satusehat.url') . '/masterdata/v1/districts?city_codes=' . $city, $headers, $body);
                $res = $client->sendAsync($request)->wait();
                $this->districtData = json_decode($res->getBody()->getContents(), true)['data'];
            } else {
                $this->districtData = [];
            }
        } catch (\Exception $e) {
            $this->districtData = [];
        }
    }

    public function setSubDistrictData($district)
    {
        try {
            $this->region_sub_district_code = null;
            if ($this->token) {
                $client = new Client();
                $headers = [
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $this->token
                ];
                $body = '';
                $request = new Request('GET', config('app.satusehat.url') . '/masterdata/v1/sub-districts?district_codes=' . $district, $headers, $body);
                $res = $client->sendAsync($request)->wait();
                $this->subDistrictData = json_decode($res->getBody()->getContents(), true)['data'];
            } else {
                $this->subDistrictData = [];
            }
        } catch (\Exception $e) {
            $this->subDistrictData = [];
        }
    }
}
