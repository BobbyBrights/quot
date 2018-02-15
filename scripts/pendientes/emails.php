<?php

function sendEmail($state, $message) {
    $html = '';
    $htmlAdmin = '';
    foreach($message['shirts'] as $sh) {
        $text = explode('-', $sh['text']);
        $title = $sh['title'];
        $quant = $sh['quant'];
        $html .= '<table  cellspacing="0" cellpadding="0" width="600" class="100p " style="border-top:1px solid #808080; padding: 10px 0 0 0;">
        	<tr>
        		<td align="left" style="color:#808080; font-size:15px">
                    <font face="\'Playfair+Display\', Times, serif">
                        <span style="font-size:19px;">'.$title.'</span><br>
                    </font>
                </td>
        	</tr>
            <tr>
                 <td height="15"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td align="left" style="color:#808080; font-size:13px;">
                    <font face="Arial, sans-serif">
                        <span style="font-size:13px;">'.$text[0].'</span><br>
                        <span style="font-size:13px;">'.$text[1].'</span><br>
                        <span style="font-size:13px;">'.$text[2].'</span><br>
                        <span style="font-size:13px;">'.$text[3].'</span><br>
                        <span style="font-size:13px;">Cantidad '.$quant.'</span><br>
                        <br>                        
                    </font>
                </td>
            </tr>
        </table>';
    }

    foreach($message['shirts'] as $sh) {
        $text = explode('-', $sh['text']);
        $title = $sh['title'];
        $quant = $sh['quant'];
        $imgBtn = $sh['img_btn'];
        $size = $sh['size'];
        $combinations = explode('-', $sh['combinations']);
        $comCuello = ($combinations[0] == 1)? "SI": "NO";
        $comPuno = ($combinations[1] == 1)? "SI": "NO";
        $comPorta = ($combinations[2] == 1)? "SI": "NO";
        $comBtn = ($combinations[3] == 1)? "SI": "NO";
        $htmlAdmin .= '<table  cellspacing="0" cellpadding="0" width="600" class="100p " style="border-top:1px solid #808080; padding: 10px 0 0 0;">
        	<tr>
        		<td align="left" style="color:#808080; font-size:15px">
                    <font face="\'Playfair+Display\', Times, serif">
                        <span style="font-size:19px;">'.$title.'</span><br>
                    </font>
                </td>
        	</tr>
            <tr>
                 <td height="15"></td>
            </tr>
        </table>
        <table>
            <tr>
                <td align="left" style="color:#808080; font-size:13px;">
                    <font face="Arial, sans-serif">
                        <span style="font-size:13px;">Nombre: ' . $message['user']['username'] . '</span><br>
                        <span style="font-size:13px;">Email: ' . $message['user']['email'] . '</span><br>
                        <span style="font-size:13px;">Ciudad: ' . $message['user']['city'] . '</span><br>
                        <span style="font-size:13px;">Dir: ' . $message['user']['address'] . '</span><br>
                        <span style="font-size:13px;">Tel: ' . $message['user']['phone'] . '</span><br>                        
                        <br>
                    </font>
                </td>
            </tr>
        </table>
        <table>
            <tr>                
                <td align="left" style="color:#808080; font-size:13px;">
                    <font face="Arial, sans-serif">
                        <span style="font-size:13px;">'.$text[0].' - combinado: ' . $comCuello . '</span><br>
                        <span style="font-size:13px;">'.$text[1].' - combinado: ' . $comPuno . '</span><br>
                        <span style="font-size:13px;">'.$text[2].' - combinado: ' . $comPorta . '</span><br>
                        <span style="font-size:13px;">'.$text[3].' - combinado: ' . $comBtn . '</span><br>
                        <span style="font-size:13px;"><img width="125px" height="125px" src="'.$imgBtn.'"/></span><br>
                        <span style="font-size:13px;">Talla '.$size.'</span><br>
                        <span style="font-size:13px;">Cantidad '.$quant.'</span><br>
                        <br>                        
                    </font>
                </td>
            </tr>
        </table>';
    }

    $titulo = "QUOT | COMPRA";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "From: no-responder < no-responder@quotstore.com >\r\n";

    if ($state == 'APPROVED') {
        $mailAdmin = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Pago exitoso</title>        
                </head>
                <body>
                    <h1>QUOT - CONFIRMACI&Oacute;N COMPRA</h1>
                    <h2>Referencia: '.$message["reference"].'</h2>
                    '.$htmlAdmin.'
                    <p>Total: '.$message["total"].'</p>
                </body>
            </html>';

        $mail = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/>
<meta name="format-detection" content="telephone=no"> 
<meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0; user-scalable=no;">
<meta http-equiv="X-UA-Compatible" content="IE=9; IE=8; IE=7; IE=EDGE" />

    <title>Orden Recibida - Quot</title>

    <style type="text/css"> 
        @media screen and (max-width: 630px) {

            /* Some resets and issue fixes */
        #outlook a { padding:0; }
        body{ width:100% !important; -webkit-text; size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; }     
        .ReadMsgBody { width: 100%; }
        .ExternalClass {width:100%;} 
        .backgroundTable {margin:0 auto; padding:0; width:100%;!important;} 
        table td {border-collapse: collapse;}
        .ExternalClass * {line-height: 115%;}           
        /* End reset */
        @import url(https://fonts.googleapis.com/css?family=Playfair+Display); /*Calling our web font*/
         /* Display block allows us to stack elements */                      
            *[class="mobile-column"] {display: block;} 
        /* Some more stacking elements */
            *[class="mob-column"] {float: none !important;width: 100% !important;} 
             /* Hide stuff */
            *[class="hide"] {display:none !important;}
             /* This sets elements to 100% width and fixes the height issues too, a god send */
            *[class="100p"] {width:100% !important; height:auto !important;}  
             /* For the 2x2 stack */            
            *[class="condensed"] {padding-bottom:40px !important; display: block;}
             /* Centers content on mobile */
            *[class="center"] {text-align:center !important; width:100% !important; height:auto !important;} 
             /* 100percent width section with 20px padding */
            *[class="100pad"] {width:100% !important; padding:20px;} 
            /* 100percent width section with 20px padding left & right */
            *[class="100padleftright"] {width:100% !important; padding:0 20px 0 20px;} 
            /* 100percent width section with 20px padding top & bottom */
            *[class="100padtopbottom"] {width:100% !important; padding:20px 0px 20px 0px;} 
            *[class="imageintro"] {width:100% !important; line-height: 90%; } 
            *[class="detalles"] {width:100% !important; border-top: 1px; border-color: #808080} 

        }
      	

    </style>
  <!-- JSON-LD markup generated by Google Structured Data Markup Helper. -->
<script type="application/ld+json">
{
  "@context" : "http://schema.org",
  "@type" : "Order",
  "acceptedOffer" : {
    "@type" : "Offer",
    "itemOffered" : {
      "@type" : "Product",
      "name" : "Albiori"
    },
    "priceSpecification" : {
      "@type" : "PriceSpecification",
      "priceCurrency" : "COP",
      "price" : "160.000"
    }
  },
  "merchant" : {
    "@type" : "Organization",
    "name" : "Quot Logo"
  },
  "orderNumber" : "000000",
  "customer" : {
    "@type" : "Person",
    "name" : "Juan"
  }
}
</script>
   
</head>

<body align="center" style="padding:0; margin:0">

<table width="275" align="center" cellspacing="0" cellpadding="0" bgcolor="#fff" class="100p">
	<tr>
		<td>
			<table border="0" cellspacing="0" cellpadding="0" width="600" class="100p">
            <tr>
                <td align="center" width="50%" class="100padtopbottom"><img src="http://quotstore.com/images/logo-quot.png" alt="Quot Logo" border="0" style="display:block" /></td>
            </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="0" width="600" class="100padtopbottom">
                <tr>
                <td height="20"></td>
                </tr>
                <td align="left" style="color:#1D3649; font-size:24px;">
                    <font face="\'Playfair+Display\', Times, serif">
                        <span style="font-size:19px;">Confirmaci&oacute;n de Pedido</span>
                        <Span style="font-size:19px;">#'.$message["reference"].'</Span>
                    </font>
                </td>
                <tr>
                    <td height="20"></td>
                </tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="0"  class="100p">
        	<tr>
        		<td background="http://quotstore.com/images/orden-intro.jpg" bgcolor="#fff" width="640" class="imageintro"> 
        				<br></br>
        				<br></br>
        				<br></br>
        				<br></br>
        				<br></br>
                        <!--[if gte mso 9]>
                        <v:rect xmlns:v="urn:schemas-microsoft-com:vml" fill="true" stroke="false" style="width:640px;">
                            <v:fill type="tile" src="images/header-bg.jpg" color="#3b464e" />
                            <v:textbox style="mso-fit-shape-to-text:true" inset="0,0,0,0">
                                <![endif]-->
                </td>
        	</tr>
        </table>
        <table border="0" cellspacing="0" cellpadding="0"  class="100p">
        	<tr>
                <td height="20"></td>
            </tr>
            <tr>
                <td align="left" style="color:#1D3649; font-size:24px;">
                    <font face="\'Playfair+Display\', Times, serif">
                        <span style="font-size:19px;">Hola '.$message["username"].',</span>
                    </font>
                </td>
            </tr>
            <tr>
                <td height="20"></td>
            </tr>
            <tr>
                <td align="left" style="color:#1D3649; font-size:15px;">
                    <font face="\'Playfair+Display\', Times, serif">
                        <span style="font-size:15px;">Gracias por tu pedido, te enviaremos un mail cuando tu camisa est&eacute; en camino. Ac&aacute; est&aacute;n los detalles de tu compra:</span>
                    </font>
		            <tr>
		                <td height="20"></td>
		            </tr>                    
                </td>
            </tr>
        </table>      
        '.$html.'
        <span style="font-size:13px;">Total: COP '.$message["total"].' </span><br>
        
        <table border="0" cellspacing="0" cellpadding="0" width="600" class="100p">
        	<tr>
                <td height="20"></td>
            </tr>
                <td align="left" style="color:#1D3649; font-size:15px;">
                    <font face="\'Playfair+Display\', Times, serif">
                        <span style="font-size:15px;">Esperamos verte pronto en Quotstore.com</span>
                    </font>
                </td>
            </tr>
        </table>

		</td>		
	</tr>
</table>
</body>
</html>';
    }

    mail($message['email'], $titulo, $mail, $headers);
    mail($message['email_admin'], $titulo . ' - admin', $mailAdmin, $headers);
    mail($message['email_control'], $titulo . ' - admin control', $mailAdmin, $headers);
}

function sendEmailSuscriptions($state, $message) {
    $html = '';
    $titulo = "QUOT | COMPRA SUSCRIPCION";
    $headers = "MIME-Version: 1.0\r\n";
    $headers .= "Content-type: text/html; charset=iso-8859-1\r\n";
    $headers .= "From: no-responder < no-responder@quotstore.com >\r\n";

    if ($state == 'APPROVED') {
        $mail = '<!DOCTYPE html>
            <html lang="en">
                <head>
                    <meta charset="UTF-8">
                    <meta name="viewport" content="width=device-width, initial-scale=1.0">
                    <title>Pago exitoso</title>        
                </head>
                <body>
                    <h1>QUOT - CONFIRMACI&Oacute;N COMPRA SUSCRIPCION</h1>
                    <h2>Referencia: ' . $message["reference"] . '</h2>
                    ' . $html . '
                    <p>Total: ' . $message["total"] . '</p>
                </body>
            </html>';
        mail($message['email'], $titulo, $mail, $headers);
        mail($message['email_admin'], $titulo . ' - admin', $mail, $headers);
        mail($message['email_control'], $titulo . ' - admin control', $mail, $headers);
    }
}
