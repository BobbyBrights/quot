<?php

function curlPayU($curlurl, $params, $method = 'POST', $headers = array()) {
    ini_set('max_execution_time', 0);
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_VERBOSE, 1);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $method);
    curl_setopt($ch, CURLOPT_URL, $curlurl);

    if ($method == 'POST') {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    }

    if ($headers) {
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
    }

    $result = curl_exec($ch);
    $ch_error = curl_error($ch);
    ini_restore('max_execution_time');

    $response = array('result' => $result);

    curl_close($ch);

    if(!$ch_error){
        return $response;
    }else{
        print 'error curl';
    }
}