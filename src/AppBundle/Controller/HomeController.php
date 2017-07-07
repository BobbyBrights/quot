<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
      return $this->render('home/index.html.twig');
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
