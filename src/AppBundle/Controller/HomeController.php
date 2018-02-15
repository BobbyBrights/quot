<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class HomeController extends Controller
{
    public function indexAction(Request $request)
    {
        $validCodes = $this->container->getParameter('access_codes');
        if(!isset($_COOKIE['codeQuotA']) || $_COOKIE['codeQuotA'] == '') {
            //return $this->redirectToRoute('pre-home');
        }
        /*if(!in_array($this->get('session')->get('codeQuot'), $validCodes)) {
            return $this->redirectToRoute('pre-home');
        }*/
        $homeJson = file_get_contents($this->container->getParameter('json_home'));
        $home = json_decode($homeJson);
        return $this->render('home/index.html.twig', array('home' => $home));
    }

    public function preHomeAction(Request $request) {
        return $this->redirectToRoute('home');
        $code = $request->query->get('usrkey');
        $validCodes = $this->container->getParameter('access_codes');
        if(isset($_COOKIE['codeQuotA'])) {
        /*if(in_array($this->get('session')->get('codeQuot'), $validCodes)) {*/
            return $this->redirectToRoute('home');
        }
        if(in_array($code, $validCodes)) {
            $this->get('session')->set('codeQuot', $code);
            setcookie('codeQuotA', $code, time() + (864000 * 3000), "/");
            return $this->redirectToRoute('home');
        } else {
            $this->get('session')->set('codeQuot', '');
            setcookie('codeQuotA', '', time() + (864000 * 3000), "/");
        }
        return $this->render('home/pre-home.html.twig');
    }

    public function newHomeAction() {
      return $this->render('home/new_home.html.twig');
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

    public function paymentMethodAction()
    {
      return $this->render('home/payment-method.html.twig');
    }

    public function cartItemsAction()
    {
      return $this->render('home/cart-items.html.twig');
    }

    public function productCustomizationAction()
    {
      return $this->render('home/product-customization.html.twig');
    }

    public function productResumeAction()
    {
      return $this->render('home/product-resume.html.twig');
    }
}
