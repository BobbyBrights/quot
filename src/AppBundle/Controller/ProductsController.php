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
        if($size == 'Talla'){
            $size = 'S';
        }
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
        if(!empty($shirtParent)){
            $shirt = $shirtParent->childs[0]->images_final;
            $vid = $shirtParent->childs[0]->vid;
            $texts = explode('-',$shirtParent->childs[0]->title);
            $title = $texts[0];
        }
        if(!empty($shirtChild)){
            $shirt = $shirtChild->childs[0]->images_final;
            $vid = $shirtChild->childs[0]->vid;
            $texts = explode('-', $shirtChild->childs[0]->title);
            $title = $texts[0];
        }
        if(!empty($shirtChild1)){
            $shirt = $shirtChild1->childs[0]->images_final;
            $vid = $shirtChild1->childs[0]->vid;
            $texts = explode('-', $shirtChild1->childs[0]->title);
            $title = $texts[0];
        }
        $size = $request->query->get('size');
        return $this->render('collections/partials/completed-shirt.html.twig', array(
            'shirt' => $shirt,
            'user_id' => $userId,
            'vid' => $vid,
            'title' => $title,
            'size' => $size,
            'parent_vid' => $parent_vid
        ));
    }
    
    public function moduleTransAction(){
        $collectionsJson = file_get_contents('http://dev-quot.pantheonsite.io/productos');
        $collections = json_decode($collectionsJson);
        $collections = array_values($collections);
        return $this->render('collections/module-trans.html.twig', array('collections' => $collections));
    }
}
