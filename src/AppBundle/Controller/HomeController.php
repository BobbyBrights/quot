<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
        $homeJson = file_get_contents('http://dev-quot.pantheonsite.io/modulos-home?a2b2c');
        $home = json_decode($homeJson);
        return $this->render('home/index.html.twig', array('home' => $home));
    }

    public function listAction()
    {
      return $this->render('home/product-list.html.twig');
    }

    public function modalAction()
    {
      return $this->render('home/product-list-modal.html.twig');
    }

    public function productToCartAction()
    {
      return $this->render('home/product-to-cart.html.twig');
    }

    public function loginAction()
    {
      return $this->render('home/login.html.twig');
    }

    public function signUpOneAction()
    {
      return $this->render('home/sign-up-one.html.twig');
    }

    public function signUpTwoAction()
    {
      return $this->render('home/sign-up-two.html.twig');
    }
}
