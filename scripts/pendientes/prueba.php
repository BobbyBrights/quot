<?php
$headers = "MIME-Version: 1.0\r\n";
$headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
$headers .= "From: no-responder < no-responder@pruebacron.com >\r\n";
$titulo = 'prueba cron ' . rand(1, 1000);
$mail = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Prueba cron</title>        
                </head>
                <body>
                    <h1>PRUEBA CRON</h1>                    
                </body>
            </html>';
mail('leva2020@gmail.com', $titulo, $mail, $headers);


/var/www/quot-symfony/scripts/pendientes/prueba.sh

/etc/cron.d

*/2 * * * * root /var/www/quot-symfony/scripts/pendientes/prueba.sh
