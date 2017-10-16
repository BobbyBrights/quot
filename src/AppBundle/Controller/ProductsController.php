<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductsController extends Controller
{
    public function collectionsAction()
    {
        $userId = 0;
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userId = $user->getId();
        }
        if(!isset($_SESSION['user_anonimo'])){
            $_SESSION['user_anonimo'] = time() . '_quot_anomino';
        }
        $collectionsJson = file_get_contents($this->container->getParameter('json_productos'));
        $collections = json_decode($collectionsJson);
        $collections = array_values($collections);
        return $this->render('collections/index.html.twig', array('collections' => $collections, 'user_anonimo' => $_SESSION['user_anonimo'], 'userId' => $userId));
    }
    
    public function customAction(Request $request, $vidParent, $vid, $size)
    {
        if($size == 'Talla'){
            $size = 'S';
        }
        $collectionsJson = file_get_contents($this->container->getParameter('json_productos'));
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
        //if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
        //    throw $this->createAccessDeniedException();
        //}
        //$user = $this->get('security.token_storage')->getToken()->getUser();
        //$userId = $user->getId();
        $queryString = array(
            'vidParent' => $request->query->get('vidParent'),
            'vid' => $request->query->get('vid'),
            'child' => $request->query->get('child'),
            'vchild' => $request->query->get('vchild'),
            'child1' => $request->query->get('child1'),
            'vchild1' => $request->query->get('vchild1'),
            'child2' => $request->query->get('child2'),
            'vchild2' => $request->query->get('vchild2'),
            'size' => $request->query->get('size'),            
        );
        return $this->render('collections/custom-detail.html.twig', $queryString);
    }
    
    public function customDetailAjaxAction(Request $request)
    {
        $collectionsJson = file_get_contents($this->container->getParameter('json_productos'));
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
        $nivel = '';
        $url = '';
        $data = array();
        if(!empty($shirtParent)){
            $data = $shirtParent;
            $nivel = '';
            $urlPrev = '/personalizar/4/33/L';
            $url = '/personalizar/detalle?vidParent=' . $request->query->get('vidParent') . '&vid=' . $request->query->get('vid') . '&size=' . $request->query->get('size');
            $urlResumen = '/resumen-camisa?vidParent=' . $request->query->get('vidParent') . '&vid=' . $request->query->get('vid') . '&size=' . $request->query->get('size');
        }
        if(!empty($shirtChild)){
            $data = $shirtChild;
            $nivel = 1;
            $urlPrev = $url;
            $url .= '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild');
            $urlResumen .= '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild');
        }
        if(!empty($shirtChild1)){
            $data = $shirtChild1;
            $urlPrev = $url;
            $url .= '&child1=' . $request->query->get('child1') . '&vchild1=' . $request->query->get('vchild1');
            $urlResumen .= '&child1=' . $request->query->get('child1') . '&vchild1=' . $request->query->get('vchild1');
            $nivel = 2;
        }
        if(!empty($shirtChild2)){
            $urlPrev = $url;
            $data = $shirtChild2;
            $url .= '&child2=' . $request->query->get('child2') . '&vchild2=' . $request->query->get('vchild2');
            $urlResumen .= '&child2=' . $request->query->get('child2') . '&vchild2=' . $request->query->get('vchild2');
            $nivel = 3;
        }
        
        $queryString = array(
            'vidParent' => $request->query->get('vidParent'),
            'vid' => $request->query->get('vid'),
            'child' => $request->query->get('child'),
            'vchild' => $request->query->get('vchild'),
            'child1' => $request->query->get('child1'),
            'vchild1' => $request->query->get('vchild1'),
            'child2' => $request->query->get('child2'),
            'vchild2' => $request->query->get('vchild2'),
            'size' => $request->query->get('size'),
        );
        return $this->render('collections/partials/custom-detail-ajax.html.twig', array('urlResumen' => $urlResumen, 'urlPrev' => $urlPrev, 'url' => $url, 'nivel' => $nivel, 'shirt' => $data, 'size' => $request->query->get('size'), 'queryString' => $queryString));
    }
    
    public function completedShirtAction(Request $request){
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        
        $collectionsJson = file_get_contents($this->container->getParameter('json_productos'));
        $collections = json_decode($collectionsJson);
        foreach($collections as $col){            
            if($col->vid == $request->query->get('vidParent')){
                $shirtParent = array();
                $shirtChild = array();
                $shirtChild1 = array();
                $shirtChild2 = array();
                foreach($col->products as $info){
                    $titleCollection = $col->title_collection;
                    if($info->vid == $request->query->get('vid')){
                        $parent_vid = $request->query->get('vid');
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
        $url = '';
        if(!empty($shirtParent)){
            $shirt = $shirtParent->childs[0]->images_final;
            $shirt_compra = $shirtParent->childs[0]->images_compra[0];
            if($request->query->get('com') == 1){
                $shirt = $shirtParent->childs[0]->images_final_combinacion;
                $shirt_compra = $shirtParent->childs[0]->images_compra[1];
            }
            $vid = $shirtParent->childs[0]->vid;
            $texts = explode('-',$shirtParent->childs[0]->title);
            $title = $texts[0];
            $neck = $shirtParent->childs[0]->detail_text[0]->value;
            $port = $shirtParent->childs[0]->detail_text[1]->value;
            $fists = $shirtParent->childs[0]->detail_text[2]->value;
            $button = $shirtParent->childs[0]->detail_text[3]->value;
            $imagenThumb = $shirtParent->childs[0]->images[1];
            $price = $shirtParent->price;
        }
        if(!empty($shirtChild)){
            $shirt = $shirtChild->childs[0]->images_final;
            $shirt_compra = $shirtChild->childs[0]->images_compra[0];
            if($request->query->get('com') == 1){
                $shirt = $shirtChild->childs[0]->images_final_combinacion;
                $shirt_compra = $shirtParent->childs[0]->images_compra[1];
            }
            $vid = $shirtChild->childs[0]->vid;
            $texts = explode('-', $shirtChild->childs[0]->title);
            $title = $texts[0];
            $neck = $shirtChild->childs[0]->detail_text[0]->value;
            $port = $shirtChild->childs[0]->detail_text[1]->value;
            $fists = $shirtChild->childs[0]->detail_text[2]->value;
            $button = $shirtChild->childs[0]->detail_text[3]->value;
            $imagenThumb1 = $shirtChild->childs[0]->images[1];
        }
        if(!empty($shirtChild1)){
            $shirt = $shirtChild1->childs[0]->images_final;
            $shirt_compra = $shirtChild1->childs[0]->images_compra[0];
            if($request->query->get('com') == 1){
                $shirt = $shirtChild1->childs[0]->images_final_combinacion;
                $shirt_compra = $shirtChild1->childs[0]->images_compra[1];
            }
            $vid = $shirtChild1->childs[0]->vid;
            $texts = explode('-', $shirtChild1->childs[0]->title);
            $title = $texts[0];
            $neck = $shirtChild1->childs[0]->detail_text[0]->value;
            $port = $shirtChild1->childs[0]->detail_text[1]->value;
            $fists = $shirtChild1->childs[0]->detail_text[2]->value;
            $button = $shirtChild1->childs[0]->detail_text[3]->value;
            $imagenThumb2 = $shirtChild1->childs[0]->images[1];
            
        }
        if(!empty($shirtChild2)){
            $shirt = $shirtChild2->childs[0]->images_final;
            $shirt_compra = $shirtChild2->childs[0]->images_compra[0];
            if($request->query->get('com') == 1){
                $shirt = $shirtChild2->childs[0]->images_final_combinacion;
                $shirt_compra = $shirtChild2->childs[0]->images_compra[1];
            }
            $vid = $shirtChild2->childs[0]->vid;
            $texts = explode('-', $shirtChild2->childs[0]->title);
            $title = $texts[0];
            $neck = $shirtChild2->childs[0]->detail_text[0]->value;
            $port = $shirtChild2->childs[0]->detail_text[1]->value;
            $fists = $shirtChild2->childs[0]->detail_text[2]->value;
            $button = $shirtChild2->childs[0]->detail_text[3]->value;
            $imagenThumb3 = $shirtChild2->childs[0]->images[1];

        }
        $imagesThumb = array($imagenThumb, $imagenThumb1, $imagenThumb2, $imagenThumb3);
        $size = $request->query->get('size');
        $urlNeck = '/personalizar/detalle?vidParent=' . $request->query->get('vidParent') . '&vid=' . $request->query->get('vid') . '&size=' . $request->query->get('size');
        $urlPort = $urlNeck . '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild');
        $urlFist = $urlPort . '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild');
        $urlButton = $urlFist . '&child1=' . $request->query->get('child1') . '&vchild1=' . $request->query->get('vchild1');

        $anonimo = false;
        if(isset($_SESSION['user_anonimo'])){
            $anonimo = $_SESSION['user_anonimo'];
        }

        return $this->render('collections/partials/completed-shirt.html.twig', array(
            'shirt' => $shirt,
            'user_id' => $userId,
            'vid' => $vid,
            'title' => $title,
            'neck' => $neck,
            'fists' => $fists,
            'port' => $port,
            'button' => $button,
            'size' => $size,
            'parent_vid' => $parent_vid,
            'url' => $url,
            'urlNeck' => $urlNeck,
            'urlPort' => $urlPort,
            'urlFist' => $urlFist,
            'urlButton' => $urlButton,
            'title_collection' => $titleCollection,
            'imagesThumb' => $imagesThumb,
            'price' => $price,
            'user_anonimo' => $anonimo,
            'shirt_thum' => $shirt_compra

        ));
    }
    
    public function moduleTransAction(){
        $collectionsJson = file_get_contents($this->container->getParameter('json_productos'));
        $collections = json_decode($collectionsJson);
        $collections = array_values($collections);
        return $this->render('collections/module-trans.html.twig', array('collections' => $collections));
    }
}
