<?php

include('connection.php');
include('functions.php');
include('vars.php');
include('emails.php');

$fecha = strtotime('-10 minute', time());
$query = 'SELECT * FROM subscriptions_payments where confirmed = 0 and update_date <= ' . $fecha . ' ORDER BY id ASC LIMIT 1';
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
        $status = $transactions['transactionResponse']['responseCode'];
        $idPay = $transactions['id'];
        $buyer = $transactionResponse['buyer']['emailAddress'];

        if ($status != 'PENDING' && $status != 'PENDING_TRANSACTION_REVIEW' && $status != 'PENDING_TRANSACTION_CONFIRMATION') {
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
                sendEmailSuscriptions('APPROVED', $message);
            }
            $query_update_sale = 'UPDATE subscriptions_payments SET transaction_id_pay = "'. $idPay .'", reference_pol = "'. $referencePol .'", confirmed = 1, status = ' . $idStatus . ', update_date = "' . time() . '" WHERE id = ' . $id_venta . ';';
            $query_update_sale_1 = 'UPDATE suscritors SET status =  ' . $idStatus . ' WHERE user_suscritor = ' . $userId . ';';
        } else {
            $query_update_sale = 'UPDATE subscriptions_payments SET transaction_id_pay = "'. $idPay .'", reference_pol = "'. $referencePol .'", confirmed = 0, update_date = "' . time() . '" WHERE id = ' . $id_venta . ';';
            $query_update_sale_1 = 'UPDATE suscritors SET status = 22 WHERE user_suscritor = ' . $userId . ';';
            $result_update_sale = mysql_query($query_update_sale) or die('Consulta fallida: ' . mysql_error());
            $result_update_sale_1 = mysql_query($query_update_sale_1) or die('Consulta fallida: ' . mysql_error());
        }
        print $id_venta;
        print ' ' . $line['status'];
        print ' ' . time();
        print '</br>';
    }
}
mysql_close($link);