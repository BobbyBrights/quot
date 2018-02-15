<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Response;
use FOS\UserBundle\Doctrine\UserManager;
use Symfony\Component\HttpFoundation\Request;

class UserController extends Controller
{
    public function addressAction(){
        return $this->render('user/address.html.twig');
    }

    public function profileAction() {
        $db = $this->getDoctrine()->getManager();
        $info = array();
        if (!$this->get('security.authorization_checker')->isGranted('IS_AUTHENTICATED_FULLY')) {
            throw $this->createAccessDeniedException();
        }
        $user = $this->get('security.token_storage')->getToken()->getUser();
        $userId = $user->getId();
        $purchases = $db->getRepository('AppBundle:PurchaseDetail')->findBy(array('user_id' => $userId, 'status' => 4));
        $dataUser = $db->getRepository('AppBundle:Address')->findBy(array('user_address' => $userId));
        $userSuscriptor = false;
        if(!empty($dataUser)) {
            $info['user']['address'] = $dataUser[0]->getAddress();
            $info['user']['phone'] = $dataUser[0]->getPhone();
        }
        if(!empty($purchases)) {
            foreach($purchases as $pr) {
                $history['texts'] = array();
                $history['title'] = $pr->getTitle();
                $history['image'] = $pr->getShirtThum();
                $history['value'] = $pr->getValue();
                $texts = explode('-', $pr->getTexts());
                $combinations = explode('-', $pr->getCombinations());
                foreach($texts as $key => $tx) {
                    if($key < 4) {
                        $com = (isset($combinations[$key]) && $combinations[$key] == 1) ? 'Si' : 'No';
                        $history['texts'][] = $tx . ' - Combinado ' . $com;
                    }
                }
                $info['user']['purchases'][] = $history;
            }
        }
        setlocale(LC_ALL,"es_ES");
        $suscriptor = $user->getSuscritor();
        $dateSuscriptor = '';
        if(!empty($suscriptor)) {
            foreach($suscriptor as $su) {
                if ($su->getStatus() == 4) {
                    $userSuscriptor = true;
                    $dateSuscriptor = $su->getPayDate();
                    $dateSuscriptorFormat = ($su->getPayDate()) ? strftime("%B de %Y",$su->getPayDate()) : '';
                    $dateFuture = strtotime('+1 year', $su->getPayDate());
                    $dateFutureFormat = ($dateFuture) ? strftime("%B de %Y",$dateFuture) : '';
                }
            }
        }

        $today = time();
        $dateCalculate = $dateFuture - $today;
        $daysCalculate = $dateCalculate/(60*60*24);
        $daysCalculate = (int)$daysCalculate;
        $endSuscription = (int)($daysCalculate/30) . ' meses';
        if($daysCalculate < 30) {
            $endSuscription = $daysCalculate . ' días';
        }

        $info['user']['suscriptor'] = $userSuscriptor;
        $info['user']['date_suscriptor'] = $dateSuscriptorFormat;
        $info['user']['end_suscription'] = $endSuscription;
        $info['user']['user_id'] = $userId;
        $info['user']['username'] = $user->getUsername();
        $info['user']['email'] = $user->getEmail();
        $info['user']['last_login'] = $user->getLastLogin()->format('Y-m-d');
        return $this->render('user/profile.html.twig', $info);
    }

    public function editInfoAction(Request $request) {
        $db = $this->getDoctrine()->getManager();
        $userManager = $this->container->get('fos_user.user_manager');
        $userId = $request->query->get('user_id');
        $email = $request->query->get('email');
        $phone = $request->query->get('phone');
        $address = $request->query->get('address');
        $emailValid = $userManager->findUserByEmail($email);
        if(!empty($emailValid)) {
            if ($emailValid->getId() != $userId) {
                return new Response('1');
            }
        }
        if($address == '' || $phone == '' || $email == '') {
            return new Response('2');
        }
        $locationUser = $db->getRepository('AppBundle:Address')->findBy(array('user_address' => $userId));
        if(!empty($locationUser)) {
            $locationUser[0]->setAddress($address);
            $locationUser[0]->setPhone($phone);
            $db->persist($locationUser[0]);
            $db->flush();
        } else {
            $addressNew = new Address();
            $addressNew->setAddress($address);
            $addressNew->setPhone($phone);
            $db->persist($addressNew);
        }
        $userObj = $userManager->findUserBy(array('id' => $userId));
        $userObj->setEmail($email);
        $db->persist($userObj);
        $db->flush();

        return new Response('0');
    }
}