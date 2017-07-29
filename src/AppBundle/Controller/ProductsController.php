<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
//use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

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
        return $this->render('collections/custom.html.twig', array('shirt' => $shirt, 'size' => $size));
    }
    
    public function customDetailAction(Request $request)
    {
        var_dump($request->query);
        $collectionsJson = file_get_contents('http://dev-quot.pantheonsite.io/productos');
        $collections = json_decode($collectionsJson);
        foreach($collections as $col){
            if($col->vid == $request->query->get('vidParent')){
                $shirt = array();
                foreach($col->products as $info){
                    if($info->vid == $request->query->get('vid')){
                        $shirt = $info;
                    }
                }
            }
        }
        return $this->render('collections/custom-detail.html.twig', array('shirt' => $shirt, 'size' => $request->query->get('size')));
    }
}
