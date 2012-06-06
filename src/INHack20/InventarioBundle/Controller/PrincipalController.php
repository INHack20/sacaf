<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

class PrincipalController extends Controller {

    /**
     * @Route("/",name="_principal")
     * @Template()
     */
    public function indexAction() {
        $user= $this->container->get('security.context')->getToken()->getUser();
        return array('user' => $user);
    }

}