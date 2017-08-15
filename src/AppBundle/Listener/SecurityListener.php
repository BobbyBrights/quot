<?php

namespace AppBundle\Listener;

use Symfony\Component\Security\Core\SecurityContextInterface;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\Security\Http\Event\InteractiveLoginEvent;
use Doctrine\ORM\EntityManager;

class SecurityListener
{
    private  $entityManager;
    
    public function __construct(SecurityContextInterface $security, Session $session, EntityManager $em)
    {
        $this->entityManager = $em;
        $this->security = $security;
        $this->session = $session;
    }

    public function onSecurityInteractiveLogin(InteractiveLoginEvent $event)
    {
        $userId = $this->security->getToken()->getUser()->getId();
        //$purchaseEm = $this->getDoctrine()->getManager();
        $shirtPreOrder = $this->entityManager->getRepository('AppBundle:PurchaseDetail')->findBy(array(
            'user_id' => $userId,
            'status' => 0,
        ));
        $count = 0;
        if(!empty($shirtPreOrder)){
            foreach ($shirtPreOrder as $pre){
                $count = (int)$pre->getQuant() + $count;
            }
        }
        $this->session->set('shirtCount', $count);
    }

}
