<?php

class APKDownloader{

    private $time_k;
    public $time_v;
    private $token;
    public $token_k;
    public $token_v;
    #public $pkg_v;
    private $pkg_k;

    public function __construct(){
        $ch = curl_init('https://apps.evozi.com/apk-downloader/');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/71.0.3578.98 Safari/537.36',
            CURLOPT_FOLLOWLOCATION => 3,
        ]);
        $data = curl_exec($ch);

        #print($data);

        # parse response and build an array of needed key/value pairs
        # the keys stays the same for some time only and so are dynamic (ig cloudflare magic)
        # example data:
        # { acddeccafeace   : 1715791041,  eeeebdacdabef: YLrOylPzrxoVCw,       aafdecabbbabfe:       IiyOyfphHOwYrACSB,   fetch: $('#forceRefetch').is(':checked')}
        if(preg_match('/{\s*(\w+)\s*:\s*(\d+),\s*(\w+)\s*:\s*(.*?),\s*(\w+)\s*:\s*(.*?),.*}/', $data, $matches)){
            #print_r($matches);

            $this->time_k = trim($matches[1]);
            $this->time_v = trim($matches[2]);
            $this->pkg_k = trim($matches[3]);
            # unused: $pkg_v = trim($matches[4]);
            $this->token_k = trim($matches[5]);
            $token_v = trim($matches[6]);

            # fetch the real token value from the random token key
            # example data:
            # var KHpwPCIBwrzGYJTuhSN  = 'CI45OfvEUX_sIfddfehlOg';
            if(preg_match('/var\s*'.$token_v.'\s*=\s*\'(.*)\'/', $data, $match)){
                #print_r($match);
                $this->token = trim(trim($match[1]), '\'');
            } else {
                throw new Exception('Can\'t fetch download token from Evozi APK Downloader.');
            }
        } else {
            throw new Exception('Can\'t fetch download token key from Evozi APK Downloader.');
        }
    }

    public function fetch($package_name, $force_refetch = false){
        $force_refetch = ($force_refetch) ? 'true' : 'false';
        $data = http_build_query([$this->time_k => $this->time_v, $this->pkg_k => $package_name, $this->token_k => $this->token, 'fetch' => $force_refetch]);
        #print($data);

        $ch = curl_init('https://api-apk.evozi.com/download');
        curl_setopt_array($ch, [
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/555.36 (KHTML, like Gecko) Chrome/171.0.1238.18 Safari/622.11',
            CURLOPT_HTTPHEADER => ['accept: application/json, text/javascript, */*; q=0.01', 'accept-language: en-US,en;q=0.9,hi;q=0.8', 'cache-control: no-cache', 'content-length: '.strlen($data), 'content-type: application/x-www-form-urlencoded; charset=UTF-8', 'origin: https://apps.evozi.com', 'pragma: no-cache', 'referer: https://apps.evozi.com/apk-downloader/'],
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => $data
        ]);
        $data = curl_exec($ch);
        $response = @json_decode($data);
        #print($data);

        if($response){
            return $response;
        } else {
            return false;
        }
    }

}
