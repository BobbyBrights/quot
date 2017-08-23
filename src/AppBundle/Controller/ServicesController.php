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
            throw $this->createAccessDeniedException();
        }
        $purchaseEm = $this->getDoctrine()->getManager();
        $purchaseDetail = new PurchaseDetail();
        $purchaseDetail->setUserId($this->get('request')->request->get('user_id'));
        $purchaseDetail->setDescription($this->get('request')->request->get('description'));
        $purchaseDetail->setTitle($this->get('request')->request->get('title'));
        $purchaseDetail->setImage($this->get('request')->request->get('shirt'));
        $purchaseDetail->setValue($this->get('request')->request->get('value'));
        $purchaseDetail->setSize($this->get('request')->request->get('size'));
        $purchaseDetail->setQuant($this->get('request')->request->get('quant'));
        $purchaseDetail->setVid($this->get('request')->request->get('vid'));
        $purchaseDetail->setStatus(0);
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
        
        $address = $this->getDoctrine()->getManager()->getRepository('AppBundle:Address')->findBy(array('user_address' => $userId));
        if(empty($address)){
            return $this->redirectToRoute('address');
        } else{
            $addressLocation = $address[0]->getAddress();
        }
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 0));
        $products = array();
        $totalPurhase = 0;
        if(!empty($purchaseDetail)){
            foreach($purchaseDetail as $pd){
                $tem['title'] = $title = $pd->getTitle();
                $tem['description'] = $pd->getDescription();
                $tem['image'] = $pd->getImage();
                $tem['value'] = $pd->getValue();                
                if(isset($products[$pd->getVid()])){
                    $tem['quant'] = $products[$pd->getVid()]['quant'] + $pd->getQuant();
                } else{
                    $tem['quant'] = $pd->getQuant();
                }
                $tem['vid'] = $pd->getVid();  
                $subTotal = $pd->getValue() * $pd->getQuant();
                $totalPurhase = $totalPurhase + $subTotal;
                $reference = 'quot_test' . $pd->getId();
                $products[$pd->getVid()] = $tem;
            }
        } else{
            return $this->redirectToRoute('products');
        }
        $merchatId = '508029';
        $accountId = '512321';
        $apiKey = '4Vj8eK4rloUd272L48hsrarnUA';
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
        $address->setName($this->get('request')->request->get('name'));
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
    
    public function resultCompraAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $purchaseOld = $this->getDoctrine()->getManager()->getRepository('AppBundle:Purchase')->findBy(array(
            'user_id' => $userId,
            'reference_pol' => $request->query->get('reference_pol'),
            'transaction_id_pay' => $request->query->get('transactionId')
        ));
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 0));
        if(empty($purchaseOld)){
            $purchase = new Purchase();
            $purchase->setUserId($userId);
            $purchase->setValue($request->query->get('TX_VALUE'));
            $purchase->setStatus($request->query->get('polTransactionState'));
            $purchase->setReferencePol($request->query->get('reference_pol'));
            $purchase->setTransactionIdPay($request->query->get('transactionId'));
            $purchaseEm = $this->getDoctrine()->getManager();
            $purchaseEm->persist($purchase);
            $purchaseEm->flush();
            
            foreach($purchaseDetail as $dt){
                $dt->setPurchaseDetail($purchase);
                $dt->setStatus($request->query->get('polTransactionState'));
                $purchaseEm->persist($dt);
                $purchaseEm->flush();
            }
        }        
        $products = array();       
        $totalPurhase = 0;
        if(!empty($purchaseDetail)){
            foreach($purchaseDetail as $pd){
                $tem['title'] = $title = $pd->getTitle();
                $tem['description'] = $pd->getDescription();
                $tem['image'] = $pd->getImage();
                $tem['value'] = $pd->getValue();                
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
}