<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
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
    
    public function customAction(Request $request, $vid)
    {
        print $vid;
        die;
        $collectionsJson = file_get_contents('http://dev-quot.pantheonsite.io/productos');
        $collections = json_decode($collectionsJson);
        $collections = array_values($collections);
        return $this->render('collections/index.html.twig', array('collections' => $collections));
    }
}
