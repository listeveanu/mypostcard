<?php

declare(strict_types=1);

namespace Cezar\Mypostcard\Services;

class HttpService implements HttpServiceInterface {

    /**
     * @param string $url
     * @param string $method
     * @param array $headers
     * @return string
     */
    public function request(string $url, string $method = 'GET', array $headers = []) : string|array
    {
        $curl = curl_init();

        if (!$curl) {
            die("Couldn't initialize a cURL handle");
        }

        curl_setopt($curl, CURLOPT_URL, $url);
        curl_setopt($curl, CURLOPT_CUSTOMREQUEST, $method);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
        curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_CONNECTTIMEOUT , 5);
        curl_setopt($curl, CURLOPT_TIMEOUT, 5);

        $response = curl_exec($curl);
        curl_close($curl);

        if (curl_errno($curl)) {
            return 'cURL error: ' . curl_error($curl);
        } else {
            return json_decode($response, true);
        }
    }
}