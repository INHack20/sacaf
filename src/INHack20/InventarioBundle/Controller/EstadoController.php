<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\InventarioBundle\Entity\Estado;

/**
 * Estado controller.
 *
 * @Route("/estado")
 */
class EstadoController extends Controller
{
    /**
     * Lists all Estado entities.
     *
     * @Route("/", name="estado")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('INHack20InventarioBundle:Estado')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Estado entity.
     *
     * @Route("/{id}/show", name="estado_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Estado')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Estado entity.');
        }

        return array(
            'entity'      => $entity,
        );
    }

}
