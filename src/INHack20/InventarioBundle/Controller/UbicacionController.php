<?php

namespace INHack20\InventarioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\InventarioBundle\Entity\Ubicacion;
use INHack20\InventarioBundle\Form\UbicacionType;
use Symfony\Component\HttpFoundation\Response;
use MakerLabs\PagerBundle\Pager;
use MakerLabs\PagerBundle\Adapter\DoctrineOrmAdapter;
use JMS\SecurityExtraBundle\Annotation\Secure;

/**
 * Ubicacion controller.
 *
 * @Route("/ubicacion")
 */
class UbicacionController extends Controller
{
    /**
     * Lists all Ubicacion entities.
     *
     * @Route("/{page}", name="ubicacion", requirements={"page"="\d+"}, defaults={"page"="1"})
     * @Template()
     */
    public function indexAction($page)
    {
        $request= $this->getRequest();
        $menu = $request->query->get('menu');
        $em = $this->getDoctrine()->getEntityManager();
        
        $qb= $em->getRepository('INHack20InventarioBundle:Ubicacion')->createQueryBuilder('u')
                ->where('u.estado = :e')
                ->setParameter('e', $this->getUser()->getEstado())
                ->orderBy('u.actualizado', 'DESC')
                ->setMaxResults(20)
                ;
        //paginador
        $adapter= new DoctrineOrmAdapter($qb);
        $pager= new Pager(
                $adapter,
                array('page' => $page, 'limit' => '20')
                );
         
            if($request->isXmlHttpRequest() && $menu == '')
            {
                return $this->render('INHack20InventarioBundle:Ubicacion:lista.html.twig', array('pager'=> $pager));
            }
        return array(
            'pager' => $pager,
        );
    }

    /**
     * Finds and displays a Ubicacion entity.
     *
     * @Route("/{id}/show", name="ubicacion_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ubicacion entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Ubicacion entity.
     *
     * @Route("/new", name="ubicacion_new")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function newAction()
    {
        $entity = new Ubicacion();
        $form   = $this->createForm(new UbicacionType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Creates a new Ubicacion entity.
     *
     * @Route("/create", name="ubicacion_create")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Ubicacion:new.html.twig")
     */
    public function createAction()
    {
        $entity  = new Ubicacion();
        $request = $this->getRequest();
        $form    = $this->createForm(new UbicacionType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Ubicacion:show', array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('ubicacion_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Ubicacion entity.
     *
     * @Route("/{id}/edit", name="ubicacion_edit")
     * @Secure(roles="ROLE_ADMIN")
     * @Template()
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ubicacion entity.');
        }

        $editForm = $this->createForm(new UbicacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Edits an existing Ubicacion entity.
     *
     * @Route("/{id}/update", name="ubicacion_update")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     * @Template("INHack20InventarioBundle:Ubicacion:edit.html.twig")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Ubicacion entity.');
        }

        $editForm   = $this->createForm(new UbicacionType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();
            
            if($request->isXmlHttpRequest())
                return $this->forward('INHack20InventarioBundle:Ubicacion:show', array('id' => $entity->getId()));
            else
                return $this->redirect($this->generateUrl('ubicacion_show', array('id' => $id)));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Ubicacion entity.
     *
     * @Route("/{id}/delete", name="ubicacion_delete")
     * @Method("post")
     * @Secure(roles="ROLE_ADMIN")
     */
    public function deleteAction($id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20InventarioBundle:Ubicacion')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Ubicacion entity.');
            }

            $em->remove($entity);
            $em->flush();
        }
        
       if($request->isXmlHttpRequest())
            return $this->forward('INHack20InventarioBundle:Ubicacion:index');
        else
            return $this->redirect($this->generateUrl('ubicacion'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
