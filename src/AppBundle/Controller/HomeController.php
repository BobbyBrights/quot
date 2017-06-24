<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;

class HomeController extends Controller
{
    public function indexAction()
    {
      /*return new Response(
        '<html><body>Hello world</body></html>'
      );*/
      return $this->render('home/index.html.twig');
    }
}
