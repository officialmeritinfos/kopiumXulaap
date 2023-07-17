<?php

namespace App\Custom;

use Illuminate\Support\Facades\Http;

class FlutterWave
{
    public $url;
    public $secKey;
    public $pubKey;
    public function __construct()
    {
        switch (config('constant.isLive')){
            case 1:
                $pubKey=config('constant.flutterwave.pubKey');
                $secKey=config('constant.flutterwave.secKey');
                break;
            default:
                $pubKey=config('constant.flutterwave.testPubKey');
                $secKey=config('constant.flutterwave.testSecKey');
                break;
        }
        $this->url = config('constant.flutterwave.url');
        $this->pubKey = $pubKey;
        $this->secKey = $secKey;
    }

    public function getBanks($country)
    {
        return Http::withHeaders([
            "Authorization" =>'Bearer '.$this->secKey
        ])->get($this->url.'banks/'.$country);
    }

    public function verifyBankAccount($data)
    {
        return Http::withHeaders([
            "Authorization" =>'Bearer '.$this->secKey
        ])->post($this->url.'accounts/resolve',$data);
    }
}
