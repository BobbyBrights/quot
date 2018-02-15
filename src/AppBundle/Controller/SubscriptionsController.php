<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use AppBundle\Entity\Suscritors;
use AppBundle\Entity\SubscriptionsPayments;
use AppBundle\Entity\User;

class SubscriptionsController extends Controller
{
    public function paymentsAction() {

    }

    public function paymentResultAction() {
        $userEmail = '';
        if ($this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            $user = $this->get('security.token_storage')->getToken()->getUser();
            $userEmail = $user->getEmail();
        }
        return $this->render('subscriptions/paymentResult.html.twig', array('email' => $userEmail));
    }

    public function culturaAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $db = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $userEmail = $user->getEmail();

        $subscriptionUser = $user->getSuscritor();
        if(!empty($subscriptionUser)) {
            foreach($subscriptionUser as $su) {
                if ($su->getStatus() == 22 /*|| $su->getStatus() == 0*/) {
                    return $this->redirectToRoute('home');
                    die('tiene una suscripcion en proceso de confirmar!!');
                } else if ($su->getStatus() == 4) {
                    return $this->redirectToRoute('home');
                    die('tiene una suscripcion activa');
                }
            }
        }

        $subscriptionIntention = new SubscriptionsPayments();
        $subscriptionIntention->setUserId($userId);
        $subscriptionIntention->setValue($this->container->getParameter('total_subscription'));
        $subscriptionIntention->setStatus(0);
        $subscriptionIntention->setConfirmed(0);
        $db->persist($subscriptionIntention);
        $db->flush();
        $idIntention = $subscriptionIntention->getId();
        $userSubscription = new Suscritors();
        $userSubscription->setUserSuscritor($user);
        $userSubscription->setStatus(0);
        $db->persist($userSubscription);
        $db->flush();

        $reference = $this->container->getParameter('reference_payment_subscription') . $idIntention;
        $merchatId = $this->container->getParameter('merchatId');
        $accountId = $this->container->getParameter('accountId');
        $apiKey = $this->container->getParameter('apiKey');
        $test = $this->container->getParameter('test_payU');
        $iva = $this->container->getParameter('iva');
        $total = $this->container->getParameter('total_subscription');
        $taxReturnBase = $total - ($total*$iva);
        $string = $apiKey . "~". $merchatId ."~". $reference ."~". (int)$total ."~COP";
        $urlSite = (isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]";
        return $this->render('subscriptions/cultura.html.twig', array(
            'reference' => $reference,
            'merchatId' => $merchatId,
            'accountId' => $accountId,
            'urlSite' => $urlSite,
            'signature' => hash( 'md5', $string ),
            'apiKey' => $apiKey,
            'total' => $total,
            'taxReturnBase' => $taxReturnBase,
            'address' => '',
            'email' => $userEmail,
            'iva' => $iva,
            'test' => $test
        ));
    }

    public function validateCulturaAction() {
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $db = $this->getDoctrine()->getManager();
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $subscriptionUser = $user->getSuscritor();
        if(!empty($subscriptionUser)) {
            foreach($subscriptionUser as $su) {
                if ($su->getStatus() == 22) {
                    $urlReferer = (isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER'] : '/';
                    if($urlReferer) {
                        return $this->redirect($urlReferer);
                    }
                    die('tiene una suscripcion en proceso de confirmar!!');
                } else if ($su->getStatus() == 4) {
                    $urlReferer = (isset($_SERVER['HTTP_REFERER']))? $_SERVER['HTTP_REFERER'] : '/';
                    if($urlReferer) {
                        return $this->redirect($urlReferer);
                    }
                    die('tiene una suscripcion activa');
                } else {
                    //$db->remove($su);
                    //$db->flush();
                    return $this->redirectToRoute('cultura');
                }
            }
        }
        return $this->redirectToRoute('cultura');
    }

}