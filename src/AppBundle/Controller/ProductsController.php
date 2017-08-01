<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function collectionsAction()
    {
        $collectionsJson = file_get_contents('http://dev-quot.pantheonsite.io/productos');
        $collections = json_decode($collectionsJson);
        $collections = array_values($collections);
        return $this->render('collections/index.html.twig', array('collections' => $collections));
    }
    
    public function customAction(Request $request, $vidParent, $vid, $size)
    {
        $collectionsJson = file_get_contents('http://dev-quot.pantheonsite.io/productos');
        $collections = json_decode($collectionsJson);
        foreach($collections as $col){
            if($col->vid == $vidParent){
                $shirt = array();
                foreach($col->products as $info){
                    if($info->vid == $vid){
                        $shirt = $info;
                    }
                }
            }
        }
        return $this->render('collections/custom.html.twig', array('shirt' => $shirt, 'size' => $size, 'vidParent' => $vidParent));
    }
    
    public function customDetailAction(Request $request)
    {
        //$user = $this->container->get('security.context')->getToken()->getUser();
        $userId = 1205;//$user->getId();
        $collectionsJson = file_get_contents('http://dev-quot.pantheonsite.io/productos');
        $collections = json_decode($collectionsJson);
        foreach($collections as $col){
            if($col->vid == $request->query->get('vidParent')){
                $shirtParent = array();
                $shirtChild = array();
                $shirtChild1 = array();
                $shirtChild2 = array();
                foreach($col->products as $info){
                    if($info->vid == $request->query->get('vid')){
                        if($request->query->get('child')){
                            foreach($info->childs as $ch){
                                if($ch->vid == $request->query->get('vchild')){
                                    $shirtChild = $ch;
                                }
                                if($request->query->get('child1')){
                                    foreach($ch->childs as $ch1){
                                        if($ch1->vid == $request->query->get('vchild1')){
                                            $shirtChild1 = $ch1;
                                        }
                                        if($request->query->get('child2')){
                                            foreach($ch1->childs as $ch2){
                                                if($ch2->vid == $request->query->get('vchild2')){
                                                    $shirtChild2 = $ch2;
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                        $shirtParent = $info;
                    }
                }
            }
        }
        $data = array();
        if(!empty($shirtParent)){
            $data = $shirtParent;
        }
        if(!empty($shirtChild)){
            $data = $shirtChild;
        }
        if(!empty($shirtChild1)){
            $data = $shirtChild1;
        }
        if(!empty($shirtChild2)){
            $data = $shirtChild2;
        }
        return $this->render('collections/custom-detail.html.twig', array('user_id' => $userId, 'shirt' => $data, 'size' => $request->query->get('size')));
    }
    
}
