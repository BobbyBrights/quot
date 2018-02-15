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
            'com_n0' => ($request->query->get('com_n0')) ? $request->query->get('com_n0') : 0,
            'com_n1' => ($request->query->get('com_n1')) ? $request->query->get('com_n1') : 0,
            'com_n2' => ($request->query->get('com_n2')) ? $request->query->get('com_n2') : 0,
            'com_n3' => ($request->query->get('com_n3')) ? $request->query->get('com_n3') : 0
        );
        return $this->render('collections/custom-detail.html.twig', $queryString);
    }
    
    public function customDetailAjaxAction(Request $request)
    {
        $collectionsJson = file_get_contents($this->container->getParameter('json_productos'));
        $collections = json_decode($collectionsJson);
        $dataCollection = array();
        foreach($collections as $col){
            if($col->vid == $request->query->get('vidParent')){
                $shirtParent = array();
                $shirtChild = array();
                $shirtChild1 = array();
                $shirtChild2 = array();
                $dataCollection['title'] = $col->title_collection;
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
                                                if($request->query->get('child3')){
                                                    foreach($ch2->childs as $ch3){
                                                        if($ch3->vid == $request->query->get('vchild3')){
                                                            $shirtChild3 = $ch3;
                                                        }
                                                    }
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
            $combinationTitle = 'Interior de cuello combinado';
            $personalTitle = 'ESCOGE TU CUELLO';
            $data = $shirtParent;
            $nivel = '';
            $comb = ($request->query->get('com_n0')) ? $request->query->get('com_n0') : 0;
            $urlPrev = '/personalizar/4/33/L';
            $url = '/personalizar/detalle?vidParent=' . $request->query->get('vidParent') . '&vid=' . $request->query->get('vid') . '&size=' . $request->query->get('size') . '&com_n0=' . $comb;
            $urlResumen = '/resumen-camisa?vidParent=' . $request->query->get('vidParent') . '&vid=' . $request->query->get('vid') . '&size=' . $request->query->get('size'). '&com_n0=' . $comb;
        }
        if(!empty($shirtChild)){
            $data = $shirtChild;
            $combinationTitle = 'Interior de los puños combinado';
            $personalTitle = 'ESCOGE TUS PUÑOS';
            $nivel = 1;
            $comb1 = ($request->query->get('com_n1')) ? $request->query->get('com_n1') : 0;
            $urlPrev = $url;
            $url .= '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild') . '&com_n1=' . $comb1;
            $urlResumen .= '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild') . '&com_n1=' . $comb1;
        }
        if(!empty($shirtChild1)){
            $data = $shirtChild1;
            $combinationTitle = 'Portañuela combinada';
            $personalTitle = '';
            //print '<pre>';var_dump($data);print '</pre>';die;
            $urlPrev = $url;
            $comb2 = ($request->query->get('com_n2')) ? $request->query->get('com_n2') : 0;
            $url .= '&child1=' . $request->query->get('child1') . '&vchild1=' . $request->query->get('vchild1') . '&com_n2=' . $comb2;
            $urlResumen .= '&child1=' . $request->query->get('child1') . '&vchild1=' . $request->query->get('vchild1') . '&com_n2=' . $comb2;
            $nivel = 2;
        }
        if(!empty($shirtChild2)){
            $combinationTitle = 'Pechera interior combinada';
            $personalTitle = 'ESCOGE TUS BOTONES';
            $urlPrev = $url;
            $data = $shirtChild2;
            $comb3 = ($request->query->get('com_n3')) ? $request->query->get('com_n3') : 0;
            $url .= '&child2=' . $request->query->get('child2') . '&vchild2=' . $request->query->get('vchild2') . '&com_n3=' . $comb3;
            $urlResumen .= '&child2=' . $request->query->get('child2') . '&vchild2=' . $request->query->get('vchild2') . '&com_n3=' . $comb3;
            $nivel = 3;
        }
        if(!empty($shirtChild3)){
            $combinationTitle = '';
            $urlPrev = $url;
            $data = $shirtChild3;
            $comb4 = ($request->query->get('com_n4')) ? $request->query->get('com_n4') : 0;
            $url .= '&child3=' . $request->query->get('child3') . '&vchild3=' . $request->query->get('vchild3') . '&com_n4=' . $comb4;
            $urlResumen .= '&child3=' . $request->query->get('child3') . '&vchild3=' . $request->query->get('vchild3') . '&com_n4=' . $comb4;
            $nivel = 4;
        }
        
        $queryString = array(
            'vidParent' => $request->query->get('vidParent'),
            'vid' => $request->query->get('vid'),
            'child' => $request->query->get('child'),
            'vchild' => $request->query->get('vchild'),
            'child1' => $request->query->get('child1'),
            'vchild1' => $request->query->get('vchild1'),
            'child2' => $request->query->get('child2'),
            'vchid3' => $request->query->get('child3'),
            'vchild3' => $request->query->get('vchild3'),
            'size' => $request->query->get('size')
        );
        return $this->render('collections/partials/custom-detail-ajax.html.twig', array('personalTitle' => $personalTitle, 'combinationTitle' => $combinationTitle, 'dataCollection' => $dataCollection, 'urlResumen' => $urlResumen, 'urlPrev' => $urlPrev, 'url' => $url, 'nivel' => $nivel, 'shirt' => $data, 'size' => $request->query->get('size'), 'queryString' => $queryString));
    }
    
    public function completedShirtAction(Request $request){
        $userId = 0;
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
                        $parent_vid = $request->query->get('vidParent');
                        $vidEdit = $request->query->get('vid');
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
                                                if($request->query->get('child3')){
                                                    foreach($ch2->childs as $ch3){
                                                        if($ch3->vid == $request->query->get('vchild3')){
                                                            $shirtChild3 = $ch3;
                                                        }
                                                    }
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
        $imagenThumb ='';
        $imagenThumb1 ='';
        $imagenThumb2 ='';
        $imagenThumb3 ='';
        if(!empty($shirtParent)){
            $shirt = $shirtParent->childs[0]->images_final;
            if (isset($shirtParent->childs[0]->images_compra[0])) {
                $shirt_compra = $shirtParent->childs[0]->images_compra[0];
            }
            $vid = $shirtParent->childs[0]->vid;
            $title = $shirtParent->childs[0]->title;
            $description = $shirtParent->childs[0]->description;
            $neck = $shirtParent->childs[0]->detail_text[0]->value;
            $port = (!empty($shirtParent->childs[0]->detail_text[1]->value)) ? $shirtParent->childs[0]->detail_text[1]->value : '';
            $fists = (!empty($shirtParent->childs[0]->detail_text[2]->value)) ? $shirtParent->childs[0]->detail_text[2]->value : '';
            $button = (!empty($shirtParent->childs[0]->detail_text[3]->value)) ? $shirtParent->childs[0]->detail_text[3]->value : '';
            $imagenThumb = $shirtParent->childs[0]->images[1];
            $neckCom = 0;
            if(isset($shirtParent->childs[0]->images_detail_resume[1]))
                $imagenThumb1 = $shirtParent->childs[0]->images_detail_resume[1];
            if(isset($shirtParent->childs[0]->images_detail_resume[2]))
                $imagenThumb2 = $shirtParent->childs[0]->images_detail_resume[2];
            if(isset($shirtParent->childs[0]->images_detail_resume[3]))
                $imagenThumb3 = $shirtParent->childs[0]->images_detail_resume[3];
            if($request->query->get('com_n0') == 1){
                $neckCom = 1;
                //$shirt = $shirtParent->childs[0]->images_final_combinacion;
                //$shirt_compra = $shirtParent->childs[0]->images_compra[1];
                $imagenThumb = $shirtParent->childs[0]->images[2];
            }
            $price = $shirtParent->price;
        }
        if(!empty($shirtChild)){
            $shirt = $shirtChild->childs[0]->images_final;
            if (isset($shirtChild->childs[0]->images_compra[0])) {
                $shirt_compra = $shirtChild->childs[0]->images_compra[0];
            }
            $vid = $shirtChild->childs[0]->vid;
            $title = $shirtChild->childs[0]->title;
            $description = $shirtChild->childs[0]->description;
            $neck = $shirtChild->childs[0]->detail_text[0]->value;
            //$port = $shirtChild->childs[0]->detail_text[1]->value;
            //$fists = $shirtChild->childs[0]->detail_text[2]->value;
            //$button = $shirtChild->childs[0]->detail_text[3]->value;
            $fistCom = 0;
            if(isset($shirtChild->childs[0]->images[1]))
                $imagenThumb1 = $shirtChild->childs[0]->images[1];
            if(isset($shirtChild->childs[0]->images_detail_resume[0]))
                $imagenThumb2 = $shirtChild->childs[0]->images_detail_resume[0];
            if(isset($shirtChild->childs[0]->images_detail_resume[1]))
                $imagenThumb3 = $shirtChild->childs[0]->images_detail_resume[1];
            if($request->query->get('com_n1') == 1){
                //$shirt = $shirtChild->childs[0]->images_final_combinacion;
                //$shirt_compra = $shirtParent->childs[0]->images_compra[1];
                $imagenThumb1 = $shirtChild->childs[0]->images[2];
                $fistCom = 1;
            }
        }
        if(!empty($shirtChild1)){
            $shirt = $shirtChild1->childs[0]->images_final;
            $shirt_compra = (isset($shirtChild1->childs[0]->images_compra[0])) ? $shirtChild1->childs[0]->images_compra[0] : '';
            $vid = $shirtChild1->childs[0]->vid;
            $title = $shirtChild1->childs[0]->title;
            $description = $shirtChild1->childs[0]->description;
            //$neck = $shirtChild1->childs[0]->detail_text[0]->value;
            //$port = $shirtChild1->childs[0]->detail_text[1]->value;
            //$fists = $shirtChild1->childs[0]->detail_text[2]->value;
            //$button = $shirtChild1->childs[0]->detail_text[3]->value;
            //var_dump($shirtChild1->childs[0]->detail_text);die;
            $portCom = 0;
            if(isset($shirtChild1->childs[0]->images[1]))
                $imagenThumb2 = $shirtChild1->childs[0]->images[1];
            if(isset($shirtChild1->childs[0]->images_detail_resume[0]))
                $imagenThumb3 = $shirtChild1->childs[0]->images_detail_resume[0];
            if($request->query->get('com_n2') == 1){
                //$shirt = $shirtChild1->childs[0]->images_final_combinacion;
                //$shirt_compra = $shirtChild1->childs[0]->images_compra[1];
                $portCom = 1;
                $imagenThumb2 = $shirtChild1->childs[0]->images[2];
            }

        }
        if(!empty($shirtChild2)){
            $shirt = $shirtChild2->childs[0]->images_final;
            $shirt_compra = (isset($shirtChild2->childs[0]->images_compra[0])) ? $shirtChild2->childs[0]->images_compra[0] : '';
            $vid = $shirtChild2->childs[0]->vid;
            $title = $shirtChild2->childs[0]->title;
            $description = $shirtChild2->childs[0]->description;
            $neck = $shirtChild2->childs[0]->detail_text[0]->value;
            $port = $shirtChild2->childs[0]->detail_text[1]->value;
            $fists = $shirtChild2->childs[0]->detail_text[2]->value;
            $button = $shirtChild2->childs[0]->detail_text[3]->value;
            $btnCom = 0;
            //var_dump($shirtChild2->childs[0]->detail_text);die;
            if(isset($shirtChild2->childs[0]->images[1]))
                $imagenThumb3 = $shirtChild2->childs[0]->images[1];
            if($request->query->get('com_n3') == 1){
                $imagenThumb3 = $shirtChild2->childs[0]->images[2];
                $btnCom = 1;
            }

        }

        if(!empty($shirtChild3)){
            $shirt = $shirtChild3->images_final;
            $shirt_compra = (isset($shirtChild3->images_compra[0])) ? $shirtChild3->images_compra[0] : '';
            $vid = $shirtChild3->vid;
            $title = $shirtChild3->title;
            $description = $shirtChild3->description;
            $neck = $shirtChild3->detail_text[0]->value;
            $port = $shirtChild3->detail_text[1]->value;
            $fists = $shirtChild3->detail_text[2]->value;
            $button = $shirtChild3->detail_text[3]->value;
            //var_dump($shirtChild3->childs[0]->detail_text);die;
            if(isset($shirtChild3->images[1]))
                $imagenThumb3 = $shirtChild3->images[1];
            if($request->query->get('com_n4') == 1){
                $imagenThumb3 = $shirtChild3->images[2];
            }
        }

        //die;
        if($neckCom == 1 && $portCom == 0) {
            $shirt = $shirtChild2->childs[0]->images_final_combinacion;
        } else if( $neckCom == 1 && $portCom == 1 ) {
            $shirt = $shirtChild2->childs[0]->field_imagen_final_cc_pc;
        } else if( $neckCom == 0 && $portCom == 1 ) {
            $shirt = $shirtChild2->childs[0]->field_imagen_final_cs_pc;
        }

        $imagesThumb = array($imagenThumb, $imagenThumb1, $imagenThumb2, $imagenThumb3);
        $size = $request->query->get('size');
        $urlNeck = '/personalizar/detalle?vidParent=' . $request->query->get('vidParent') . '&vid=' . $request->query->get('vid') . '&size=' . $request->query->get('size');
        $urlPort = $urlNeck . '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild');
        $urlFist = $urlPort . '&child=' . $request->query->get('child') . '&vchild=' . $request->query->get('vchild');
        $urlButton = $urlFist . '&child1=' . $request->query->get('child1') . '&vchild1=' . $request->query->get('vchild1');
        $urlPnl = $urlButton . '&child2=' . $request->query->get('child2') . '&vchild2=' . $request->query->get('vchild2');

        $anonimo = false;
        if(isset($_SESSION['user_anonimo'])){
            $anonimo = $_SESSION['user_anonimo'];
        }

        return $this->render('collections/partials/completed-shirt.html.twig', array(
            'shirt' => $shirt,
            'user_id' => $userId,
            'vid' => $vid,
            'vidEdit' => $vidEdit,
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
            'urlPnl' => $urlPnl,
            'title_collection' => $titleCollection,
            'imagesThumb' => $imagesThumb,
            'price' => $price,
            'user_anonimo' => $anonimo,
            'shirt_thum' => $shirt_compra,
            'description' => $description,
            'img_btn' => (isset($_COOKIE['hilo'])) ? $_COOKIE['hilo'] : '',
            'neckCom' => $neckCom,
            'fistCom' => $fistCom,
            'portCom' => $portCom,
            'btnCom' => $btnCom

        ));
    }
    
    public function moduleTransAction(){
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
        return $this->render('collections/module-trans.html.twig', array('collections' => $collections, 'user_anonimo' => $_SESSION['user_anonimo'], 'userId' => $userId));
    }
}
