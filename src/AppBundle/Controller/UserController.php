<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class UserController extends Controller
{
    public function addressAction(){
        return $this->render('user/address.html.twig');
    }
}
