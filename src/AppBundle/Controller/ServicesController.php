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
        $purchaseDetail = new PurchaseDetail();
        $purchaseEm = $this->getDoctrine()->getManager();
        $purchaseDetail->setUserId(1205);
        $purchaseDetail->setDescription('descripcion de la camisa');
        $purchaseDetail->setTitle('titulo camisa');
        $purchaseDetail->setImage('rutaimagen');
        $purchaseDetail->setValue(19000);
        $purchaseDetail->setStatus(2);
        $purchaseEm->persist($purchaseDetail);
        $purchaseEm->flush();
        $idPreOrder = $purchaseDetail->getId();
        return new Response('salvando preorder ' . $idPreOrder);
    }
    
    public function listProductsOrderAction(Request $request){
        $userId = $request->query->get('user_id');
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
                $reference = $pd->getId();
                $products[] = $tem;
            }
        }
        $string = "FhkmbRHL3nAveKjQ1IKEr6Qh8i~630936~". $reference ."~". $totalPurhase ."~COP";
        return $this->render('services/purchase-sumary.html.twig',                
                array(
                    'products' => $products,
                    'total' => $totalPurhase,
                    'taxReturnBase' => $totalPurhase - ($totalPurhase*19)/100,
                    'iva' => $totalPurhase*1.19,
                    'reference' => $reference,
                    'address' => 5000,
                    'signature' => hash( 'sha256', $string ),
                ));
    }
}
