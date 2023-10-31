<?php

namespace App\Library;

use Illuminate\Support\Facades\Http;

class QuantifyCrypto
{
    protected $headers;

    public function __construct()
    {
        $this->headers = [
            'Qc-Access-Key-Id'  => env('QC_API_KEY'),
            'Qc-Secret-Key'  => env('QC_API_SECRET')
        ];
    }


    public function getListAllCoins()
    {
        return  $this->curl("https://quantifycrypto.com/api/v1/coins/list");
    }

    public function getCoinDetails($coin)
    {
       return $this->curl("https://quantifycrypto.com/api/v1/coins/".$coin);
    }

    public function getCoinDetailsInitTable()
    {
        return $this->curl("https://quantifycrypto.com/api/v1/common/init-table");
    }

    private function curl($URL)
    {
        $curl = curl_init();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $URL,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                "QC-Access-Key:".env('QC_API_KEY'),
                "QC-Secret-Key:".env('QC_API_SECRET')
            ),
        ));
        $response = curl_exec($curl);
        curl_close($curl);
        return json_decode($response, true);
    }


     /**
     * Returns algorithm trend values.
     * @param Interge $rank_from [Starting rank position (by market cap) of the first coin that will be returned. Defaults to 1]
     * @param Interge $rank_to [Ending rank position (by market cap) of the last coin that will be returned. Defaults to 10]
     * @param Interge $rank_from [The candlestick duration used for the trend algorithm.
     * Values are daily, short, mid, long, distant, mean, all. Defaults to "all".
     * Multiple parameters need to be comma-separated. Example: mid, long]
     * @return \Illuminate\Http\Response
     */
    public function getTrend($rank_from=null, $rank_to=null, $time_period=null){

        $response = Http::withHeaders($this->headers)->get('https://quantifycrypto.com/api/v1.0-beta/trend', [
            'rank_from'     => $rank_from,
            'rank_to'       => $rank_to,
            'time_period'   => $time_period
        ]);

        return $response->json();
    }
}
