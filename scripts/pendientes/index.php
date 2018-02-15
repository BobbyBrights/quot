<?php

include('connection.php');
include('functions.php');
include('vars.php');
include('emails.php');

$fecha = strtotime('-10 minute', time());
$query = 'SELECT * FROM purchases where confirmed = 0 and update_date <= ' . $fecha . ' ORDER BY id ASC LIMIT 1';
$result = mysql_query($query) or die('Consulta fallida: ' . mysql_error());

while ($line = mysql_fetch_array($result, MYSQL_ASSOC)) {
    $id_venta = (int) $line['id'];
    $user_id = (int) $line['user_id'];
    $post_data = array(
        'test' => false,
        'language' => 'en',
        'command' => 'ORDER_DETAIL_BY_REFERENCE_CODE',
        'merchant' => array(
            'apiLogin' => '6JaueIqL3h8Kilq',
            'apiKey' => 'FhkmbRHL3nAveKjQ1IKEr6Qh8i'
        ),
        'details' => array(
            'referenceCode' => $line['reference']
        )
    );
    $post_data = json_encode($post_data, true);
    $consultUrl = 'https://api.payulatam.com/reports-api/4.0/service.cgi';
    $headers = array(
        'Content-Type: application/json',
        'charset=utf-8',
        'Accept: application/json',
        'Content-Length: ' . strlen($post_data)
    );
    $response = curlPayU($consultUrl, $post_data, 'POST', $headers);
    if (isset($response) && $response != "") {
        $jsonResponse = json_decode($response['result'], true);
        $transactionResponse = ($jsonResponse['result']['payload'][0]);
        $referencePol = $transactionResponse['id'];
        $transactions = end($transactionResponse['transactions']);
        //$status = $transactions['transactionResponse']['responseCode'];
        $status = $transactions['transactionResponse']['state'];
        $idPay = $transactions['id'];
        $buyer = $transactionResponse['buyer']['emailAddress'];

        if ($status != 'PENDING'/* && $status != 'PENDING_TRANSACTION_REVIEW' && $status != 'PENDING_TRANSACTION_CONFIRMATION'*/) {
            $idStatus = 9;
            if ($status == 'APPROVED') {
                $idStatus = 4;
                $message['email_admin'] = 'info@quotstore.com';
                $message['email_control'] = 'leva2020@gmail.com';
                $message['email'] = $buyer;
                $message['reference'] = $line['reference'];
                $message['total'] = $line['value'];
                $userId = $line['user_id'];
                $queryUser = 'SELECT * FROM fos_user WHERE id = ' . $userId . ' ';
                $resultUser = mysql_query($queryUser) or die('Consulta fallida: ' . mysql_error());
                while ($lineU = mysql_fetch_array($resultUser, MYSQL_ASSOC)) {
                    $message['username'] = $lineU['username'];
                }
                $queryDetail = 'SELECT * FROM purchases_detail where purchases_details = ' . $id_venta .' ';
                $resultDetail = mysql_query($queryDetail) or die('Consulta fallida: ' . mysql_error());
                $i = 0;
                while ($lineD = mysql_fetch_array($resultDetail, MYSQL_ASSOC)) {
                    $message['shirts'][$i]['title'] = $lineD['title'];
                    $message['shirts'][$i]['text'] = $lineD['texts'];
                    $message['shirts'][$i]['img_btn'] = $lineD['img_btn'];
                    $message['shirts'][$i]['quant'] = $lineD['quant'];
                    $message['shirts'][$i]['size'] = $lineD['size'];
                    $message['shirts'][$i]['combinations'] = $lineD['combinations'];
                    $i++;
                }

                $queryUser = 'SELECT * FROM address where user_address = ' . $user_id .' ';
                $resultQUser = mysql_query($queryUser) or die('Consulta fallida: ' . mysql_error());
                while ($lineU = mysql_fetch_array($resultQUser, MYSQL_ASSOC)) {
                    $address = $lineU['address'];
                    $city = $lineU['city'];
                    $phone = $lineU['phone'];
                }
                $queryUser1 = 'SELECT * FROM fos_user where id = ' . $user_id .' ';
                $resultQUser1 = mysql_query($queryUser1) or die('Consulta fallida: ' . mysql_error());
                while ($lineU1 = mysql_fetch_array($resultQUser1, MYSQL_ASSOC)) {
                    $email = $lineU1['email'];
                    $username = $lineU1['username'];
                }
                $message['user']['address'] = $address;
                $message['user']['city'] = $city;
                $message['user']['phone'] = $phone;
                $message['user']['email'] = $email;
                $message['user']['username'] = $username;

                sendEmail('APPROVED', $message);
            }
            $query_update_sale = 'UPDATE purchases SET transaction_id_pay = "'. $idPay .'", reference_pol = "'. $referencePol .'", confirmed = 1, status = ' . $idStatus . ', update_date = "' . time() . '" WHERE id = ' . $id_venta . ';';
            $query_update_sale_1 = 'UPDATE purchases_detail SET status =  ' . $idStatus . ' WHERE purchases_details = ' . $id_venta . ';';
            $result_update_sale = mysql_query($query_update_sale) or die('Consulta fallida: ' . mysql_error());
            $result_update_sale_1 = mysql_query($query_update_sale_1) or die('Consulta fallida: ' . mysql_error());
        } else {
            $query_update_sale = 'UPDATE purchases SET transaction_id_pay = "'. $idPay .'", reference_pol = "'. $referencePol .'", confirmed = 0, update_date = "' . time() . '" WHERE id = ' . $id_venta . ';';
            $query_update_sale_1 = 'UPDATE purchases_detail SET status = 2 WHERE purchases_details = ' . $id_venta . ';';
            $result_update_sale = mysql_query($query_update_sale) or die('Consulta fallida: ' . mysql_error());
            $result_update_sale_1 = mysql_query($query_update_sale_1) or die('Consulta fallida: ' . mysql_error());
        }
        print $id_venta;
        print ' ' . $line['status'];
        print ' ' . time();
        print '</br>';
    }
}
/*
mysql_/free_result($result);
mysql_/free_result($resultUser);
mysql_/free_result($resultDetail);
*/
mysql_close($link);



