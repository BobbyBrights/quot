<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\PurchaseDetail;
use AppBundle\Entity\Newsletter;
use AppBundle\Entity\Address;
use Symfony\Component\HttpFoundation\Request;

class ServicesController extends Controller
{
    public function savePurchaseAction(Request $request)
    {
        $purchase = new Purchase();
        $purchase->setUserId(1205);
        $purchase->setValue(19900);
        $purchase->setStatus(3);
        $purchaseEm = $this->getDoctrine()->getManager();
        $purchaseEm->persist($purchase);
        $purchaseEm->flush();
        $idPurchase = $purchase->getId();
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => 1205));
        foreach($purchaseDetail as $dt){
            $dt->setPurchaseDetail($purchase);
            $dt->setStatus(3);
            $purchaseEm->persist($dt);
            $purchaseEm->flush();
        }
        return new Response('salvando orden ' . $idPurchase);
    }
    
    public function savePreOrderAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            return new Response(99999);
            //throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $subscriptionUser = $user->getSuscritor();
        $valExtra = 30000;
        if(!empty($subscriptionUser)) {
            foreach($subscriptionUser as $su) {
                if ($su->getStatus() == 4) {
                    $valExtra = 0;
                }
            }
        }
        $value= (int)$this->get('request')->request->get('value');// + $valExtra;
        $combinations = $this->get('request')->request->get('neckCom') . '-' . $this->get('request')->request->get('fistCom') . '-' . $this->get('request')->request->get('portCom') . '-' . $this->get('request')->request->get('btnCom');
        $purchaseEm = $this->getDoctrine()->getManager();
        $purchaseDetail = new PurchaseDetail();
        $purchaseDetail->setUserId($userId);
        $purchaseDetail->setDescription($this->get('request')->request->get('description'));
        $purchaseDetail->setTitle($this->get('request')->request->get('title'));
        $purchaseDetail->setImage($this->get('request')->request->get('shirt'));
        $purchaseDetail->setValue($value);
        $purchaseDetail->setSize($this->get('request')->request->get('size'));
        $purchaseDetail->setQuant($this->get('request')->request->get('quant'));
        $purchaseDetail->setVid($this->get('request')->request->get('vid'));
        $purchaseDetail->setVidParent($this->get('request')->request->get('vid_parent'));
        $purchaseDetail->setUserAnonimo($this->get('request')->request->get('anonimo'));
        $purchaseDetail->setShirtThum($this->get('request')->request->get('shirt_thum'));
        $purchaseDetail->setTexts($this->get('request')->request->get('texts'));
        $purchaseDetail->setStatus(0);
        $purchaseDetail->setImgBtn($this->get('request')->request->get('img_btn'));
        $purchaseDetail->setVidEdit($this->get('request')->request->get('vidEdit'));
        $purchaseDetail->setCombinations($combinations);
        $purchaseDetail->setCustom($this->get('request')->request->get('custom'));
        $purchaseEm->persist($purchaseDetail);
        $purchaseEm->flush();

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $shirtPreOrder = $purchaseEm->getRepository('AppBundle:PurchaseDetail')->findBy(array(
            'user_id' => $userId,
            'status' => 0,
        ));
        $count = 0;
        
        if(!empty($shirtPreOrder)){
            foreach ($shirtPreOrder as $pre){
                $count = (int)$pre->getQuant() + $count;
            }
        }
        $this->get('session')->set('shirtCount', $count);
        
        return new Response($count);
    }
    
    public function listProductsOrderAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $userEmail = $user->getEmail();
        $suscriptor = $user->getSuscritor();
        $suscriptorState = 0;
        foreach ($suscriptor as $su) {
            if($su->getStatus() == 4) {
                $suscriptorState = $su->getStatus();
            }
        }

        $purchaseDetailAnonimo = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_anonimo' => $_SESSION['user_anonimo'], 'status' => 0));
        if(!empty($purchaseDetailAnonimo)){
            foreach($purchaseDetailAnonimo as $an){
                $an->setUserId($userId);
            }
            $this->getDoctrine()->getManager()->flush();
        }

        $address = $this->getDoctrine()->getManager()->getRepository('AppBundle:Address')->findBy(array('user_address' => $userId));
        if(empty($address)){
            return $this->redirectToRoute('address');
        } else{
            $addressLocation = $address[0]->getAddress();
        }
        $urlSite = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 0));
        $products = array();
        $totalPurhase = 0;
        if(!empty($purchaseDetail)){
            foreach($purchaseDetail as $pd){
                $value = $pd->getValue();
                if($pd->getCustom() && $suscriptorState  != 4) {
                    $value = $pd->getValue() + 30000;
                }
                $tem['title'] = $title = $pd->getTitle();
                $tem['description'] = $pd->getDescription();
                $tem['image'] = $pd->getShirtThum();
                $tem['value'] = $value;

                if(isset($products[$pd->getVid()])){
                    $tem['quant'] = $products[$pd->getVid()]['quant'] + $pd->getQuant();
                } else{
                    $tem['quant'] = $pd->getQuant();
                }
                $tem['vid'] = $pd->getVid();  
                $subTotal = $pd->getValue() * $pd->getQuant();
                $totalPurhase = $totalPurhase + $subTotal;
                $reference = 'compra_quot_' . $pd->getId();
                $products[/*$pd->getVid()*/] = $tem;
            }
        } else{
            return $this->redirectToRoute('products');
        }
        $merchatId = '630936';
        $accountId = '633275';
        $apiKey = 'FhkmbRHL3nAveKjQ1IKEr6Qh8i';
        $iva = 0.19;
        $string = $apiKey . "~". $merchatId ."~". $reference ."~". (int)$totalPurhase ."~COP";
        return $this->render('services/purchase-sumary.html.twig',                
                array(
                    'products' => $products,
                    'total' => $totalPurhase,
                    'taxReturnBase' => $totalPurhase - ($totalPurhase*$iva),
                    'iva' => $totalPurhase*$iva,
                    'reference' => $reference,
                    'signature' => hash( 'md5', $string ),
                    'address' => $addressLocation,
                    'merchatId' => $merchatId,
                    'apiKey' => $apiKey,
                    'accountId' => $accountId,
                    'email' => $userEmail,
                    'urlSite' => $urlSite,
                ));
    }
    
    public function newsletterAction(Request $request){
        $email = $this->get('request')->request->get('email');
        if($email == ''){
            return new Response('Debes ingresar un correo');
        }
        $emailExist = $this->getDoctrine()->getManager()->getRepository('AppBundle:Newsletter')->findBy(array('email' => $email));
        if(!empty($emailExist)){
            return new Response('El correo ' . $email . ' ya se encuentra suscrito');
        } else{
            $newsletter = new Newsletter();
            $newsletter->setEmail($email);
            $newsletterEm = $this->getDoctrine()->getManager();
            $newsletterEm->persist($newsletter);
            $newsletterEm->flush();
            return new Response('Felicidades!');
        }
    }

    public function saveAddressAction(Request $request){

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $address = new Address();
        $address->setName($this->get('request')->request->get('address'));
        $address->setAddress($this->get('request')->request->get('address'));
        $address->setCity($this->get('request')->request->get('city'));
        $address->setState($this->get('request')->request->get('state'));
        $address->setPhone($this->get('request')->request->get('phone'));
        $address->setUserAddress($user);
        $addressEm = $this->getDoctrine()->getManager();
        $addressEm->persist($address);
        $addressEm->flush();
        return $this->redirectToRoute('list-products-orders');
    }

    public function saveOrderAction(Request $request) {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $purchaseOld = $this->getDoctrine()->getManager()->getRepository('AppBundle:Purchase')->findBy(array(
            'user_id' => $userId,
            'reference' => $request->query->get('referenceCode')
        ));

        $purchaseDetailUpdate = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 0));
        $purchaseDetailUpdateEm = $this->getDoctrine()->getManager();

        if(empty($purchaseOld)) {
            $purchase = new Purchase();
            $purchase->setUserId($userId);
            $purchase->setValue($request->query->get('TX_VALUE'));
            $purchase->setStatus(0);
            $purchase->setReference($request->query->get('referenceCode'));
            $purchase->setConfirmed(0);
            $purchase->setPayDate(time());
            $purchase->setUpdateDate(time());
            $purchaseEm = $this->getDoctrine()->getManager();
            $purchaseEm->persist($purchase);
            $purchaseEm->flush();
        }

        if($purchaseDetailUpdate) {
            if($purchaseOld) {
                $purchaseUp = $purchaseOld[0];
            } else {
                $purchaseUp = $purchase;
            }
            foreach ($purchaseDetailUpdate as $pd) {
                $pd->setPurchaseDetail($purchaseUp);
                //$pd->setStatus(1);
                $purchaseDetailUpdateEm->persist($pd);
                $purchaseDetailUpdateEm->flush();
            }
        }

        return new Response($request->query->get('referenceCode'));
    }
    
    public function resultCompraAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        /*$purchaseOld = $this->getDoctrine()->getManager()->getRepository('AppBundle:Purchase')->findBy(array(
            'user_id' => $userId,
            'reference' => $request->query->get('referenceCode'),
            //'transaction_id_pay' => $request->query->get('transactionId')
        ));*/
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 0));
        /*if(empty($purchaseOld)){
            $purchase = new Purchase();
            $purchase->setUserId($userId);
            $purchase->setValue($request->query->get('TX_VALUE'));
            $purchase->setStatus($request->query->get('polTransactionState'));
            $purchase->setReferencePol($request->query->get('reference_pol'));
            $purchase->setTransactionIdPay($request->query->get('transactionId'));
            $purchase->setPayDate(time());
            //$purchase->setUpdateDate(date);
            $purchaseEm = $this->getDoctrine()->getManager();
            $purchaseEm->persist($purchase);
            $purchaseEm->flush();
            
            foreach($purchaseDetail as $dt){
                $dt->setPurchaseDetail($purchase);
                $dt->setStatus($request->query->get('polTransactionState'));
                $purchaseEm->persist($dt);
                $purchaseEm->flush();
            }
        } else{
            print $idPurchaseOld = $purchaseOld[0]->getId();
            $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'purchase_detail' => $purchaseOld[0]));
        }*/
        $products = array();       
        $totalPurhase = 0;
        if(!empty($purchaseDetail)){
            foreach($purchaseDetail as $pd){
                $tem['title'] = $pd->getTitle();
                $tem['description'] = $pd->getDescription();
                $tem['image'] = $pd->getShirtThum();
                $tem['value'] = $pd->getValue();
                $pay_date = $pd->getPurchaseDetail()->getPayDate();
                $tem['pay_date'] = date('Y-m-d', $pay_date);
                if(isset($products[$pd->getVid()])){
                    $tem['quant'] = $products[$pd->getVid()]['quant'] + $pd->getQuant();
                } else{
                    $tem['quant'] = $pd->getQuant();
                }
                $tem['vid'] = $pd->getVid();  
                $subTotal = $pd->getValue() * $pd->getQuant();
                $totalPurhase = $totalPurhase + $subTotal;
                $products[$pd->getVid()] = $tem;
            }
        }
        $this->get('session')->set('shirtCount', 0);
        return $this->render('services/purchase-sumary-pay.html.twig',                
                array(
                    'products' => array_values($products)
                ));
        
    }
    
    public function countShirtAction(Request $request){
        $count = 0;
        $vid = $request->query->get('vidParent');
        $title = $request->query->get('title');
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('status' => 4, 'title' => $title));
        if($purchaseDetail){
            foreach ($purchaseDetail as $pre){
                $count = (int)$pre->getQuant() + $count;            
            }
        }
        $this->get('session')->set('countShirt_' . $vid, $count);
        setcookie('count_shirt_' . $vid, $count . ' de 50', time() + (864000 * 3000), "/");
        return new Response($count . ' de 50');
    }
    
    public function cartPurchaseAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $_SESSION['user_anonimo'] = (isset($_SESSION['user_anonimo'])) ? $_SESSION['user_anonimo'] : '';
        //$purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_anonimo' => $_SESSION['user_anonimo'], 'status' => 0));
        $suscriptorState = 0;

        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $userEmail = $user->getEmail();
        $suscriptor = $user->getSuscritor();
        foreach ($suscriptor as $su) {
            if($su->getStatus() == 4) {
                $suscriptorState = $su->getStatus();
            }
        }
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 0));

        $products = array();
        $totalPurhase = 0;
        if(!empty($purchaseDetail)){
            foreach($purchaseDetail as $pd){
                $tem['id'] = $title = $pd->getId();
                $tem['title'] = $title = $pd->getTitle();
                $tem['description'] = $pd->getDescription();
                $tem['image'] = $pd->getShirtThum();
                $tem['value'] = $pd->getValue();                
                $tem['size'] = $pd->getSize();                
                if(isset($products[$pd->getVid()])){
                    $tem['quant'] = $products[$pd->getVid()]['quant'] + $pd->getQuant();
                } else{
                    $tem['quant'] = $pd->getQuant();
                }
                $tem['vid'] = $pd->getVid();  
                $tem['vid_edit'] = $pd->getVidEdit();
                $tem['parent_vid'] = $pd->getVidParent();
                $tem['texts'] = $pd->getTexts();
                $tem['custom'] = $pd->getCustom();
                $subTotal = $pd->getValue() * $pd->getQuant();
                $totalPurhase = $totalPurhase + $subTotal;
                $products[] = $tem;
            }
        } else{
            return $this->redirectToRoute('products');
        }
        //print '<pre>';var_dump($products);print '</pre>';die;
        return $this->render('services/cart-purchase.html.twig',
            array(
                'suscriptor' => $suscriptorState,
                'products' => $products
            ));
    }

    public function deletePurchaseAction(Request $request) {
        $id = $request->query->get('id');
        $count = $request->query->get('count');
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->find($id);
        $quant = $purchaseDetail->getQuant();
        $em = $this->getDoctrine()->getEntityManager();
        $em->remove($purchaseDetail);
        $em->flush();
        $total = $count - $quant;
        if($total < 0) {
            $total = 0;
        }
        $this->get('session')->set('shirtCount', $total);
        return new Response($total);
    }
}