<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use AppBundle\Entity\Purchase;
use AppBundle\Entity\PurchaseDetail;
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
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $shirtPreOrder = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array(
            'user_id' => $userId,
            'vid' => $this->get('request')->request->get('vid'),
        ));
        
        $purchaseEm = $this->getDoctrine()->getManager();
        if(!empty($shirtPreOrder)){
            $quantPre = $shirtPreOrder[0]->getQuant(); 
            $shirtPreOrder[0]->setQuant($quantPre + 1);
            $idPreOrder = $shirtPreOrder[0]->getId();
            $purchaseEm->persist($shirtPreOrder[0]);
            $purchaseEm->flush();
        } else{        
            $quant = 1;
            $purchaseDetail = new PurchaseDetail();
            $purchaseDetail->setUserId($this->get('request')->request->get('user_id'));
            $purchaseDetail->setDescription($this->get('request')->request->get('description'));
            $purchaseDetail->setTitle($this->get('request')->request->get('title'));
            $purchaseDetail->setImage($this->get('request')->request->get('shirt'));
            $purchaseDetail->setValue($this->get('request')->request->get('value'));
            $purchaseDetail->setSize($this->get('request')->request->get('size'));
            $purchaseDetail->setQuant($quant);
            $purchaseDetail->setVid($this->get('request')->request->get('vid'));
            $purchaseDetail->setStatus(0);
            $purchaseEm->persist($purchaseDetail);
            $purchaseEm->flush();
            $idPreOrder = $purchaseDetail->getId();
        }
        return new Response('salvando preorder ' . $idPreOrder);
    }
    
    public function listProductsOrderAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $purchaseDetail = $this->getDoctrine()->getManager()->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId));
        $products = array();
        $totalPurhase = 0;
        if(!empty($purchaseDetail)){
            foreach($purchaseDetail as $pd){
                $tem['title'] = $title = $pd->getTitle();
                $tem['description'] = $pd->getDescription();
                $tem['image'] = $pd->getImage();
                $tem['value'] = $pd->getValue();
                $totalPurhase = $totalPurhase + $pd->getValue();
                $reference = 'TestPayUquot';//$pd->getId();
                $products[] = $tem;
            }
        }
        $string = "4Vj8eK4rloUd272L48hsrarnUA~508029~". $reference ."~". $totalPurhase ."~COP";
        return $this->render('services/purchase-sumary.html.twig',                
                array(
                    'products' => $products,
                    'total' => $totalPurhase,
                    'taxReturnBase' => $totalPurhase - ($totalPurhase*19)/100,
                    'iva' => $totalPurhase*0.19,
                    'reference' => $reference,
                    'address' => 5000,
                    'signature' => hash( 'sha256', $string ),
                ));
    }
}
