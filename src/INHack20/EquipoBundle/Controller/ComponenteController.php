<?php

namespace INHack20\EquipoBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use INHack20\EquipoBundle\Entity\Componente;
use INHack20\EquipoBundle\Form\ComponenteType;

/**
 * Componente controller.
 *
 * @Route("/componente")
 */
class ComponenteController extends Controller
{
    /**
     * Lists all Componente entities.
     *
     * @Route("/", name="componente")
     * @Template()
     */
    public function indexAction()
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entities = $em->getRepository('INHack20EquipoBundle:Componente')->findAll();

        return array(
            'entities' => $entities,
        );
    }

    /**
     * Finds and displays a Componente entity.
     *
     * @Route("/{id}/show", name="componente_show")
     * @Template()
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:Componente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Componente entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Displays a form to create a new Componente entity.
     *
     * @Route("/new/{equipo_id}", name="componente_new", requirements={"equipo_id" = "\d+"}, defaults={"equipo_id"="0"})
     * @Template()
     */
    public function newAction($equipo_id)
    {
        $entity = new Componente();
        $form   = $this->createForm(new ComponenteType(), $entity);

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
            'equipo_id' => $equipo_id,
        );
    }

    /**
     * Creates a new Componente entity.
     *
     * @Route("/{equipo_id}/create", name="componente_create", requirements={"equipo_id" = "\d+"}, defaults={"equipo_id"="0"})
     * @Method("post")
     * @Template("INHack20EquipoBundle:Componente:new.html.twig")
     */
    public function createAction($equipo_id)
    {
        $entity  = new Componente();
        $request = $this->getRequest();
        $form    = $this->createForm(new ComponenteType(), $entity);
        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            
            $equipo = $em->getRepository('INHack20EquipoBundle:Equipo')->find($equipo_id);

                if (!$equipo) {
                    throw $this->createNotFoundException('Unable to find Equipo entity.');
                }
            $entity->setEquipo($equipo);
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest()){
                if($equipo_id > 0)
                {
                    return $this->render('INHack20EquipoBundle:Componente:lista.html.twig', array('entities' => $equipo->getComponentes(), 'equipo_id' => $equipo_id ));
                }
                else
                    return $this->forward('INHack20EquipoBundle:Componente:show', array('id' => $entity->getId()));
            }
            else
                return $this->redirect($this->generateUrl('componente_show', array('id' => $entity->getId())));
        }

        return array(
            'entity' => $entity,
            'form'   => $form->createView(),
        );
    }

    /**
     * Displays a form to edit an existing Componente entity.
     *
     * @Route("/{equipo_id}/{id}/edit", name="componente_edit", requirements={"equipo_id" = "\d+"}, defaults={"equipo_id"="0"})
     * @Template()
     */
    public function editAction($equipo_id,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:Componente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Componente entity.');
        }

        $editForm = $this->createForm(new ComponenteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
            'equipo_id' => $equipo_id,
        );
    }

    /**
     * Edits an existing Componente entity.
     *
     * @Route("/{equipo_id}/{id}/update", name="componente_update", requirements={"equipo_id" = "\d+"}, defaults={"equipo_id"="0"})
     * @Method("post")
     * @Template("INHack20EquipoBundle:Componente:edit.html.twig")
     */
    public function updateAction($equipo_id,$id)
    {
        $em = $this->getDoctrine()->getEntityManager();

        $entity = $em->getRepository('INHack20EquipoBundle:Componente')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find Componente entity.');
        }

        $editForm   = $this->createForm(new ComponenteType(), $entity);
        $deleteForm = $this->createDeleteForm($id);

        $request = $this->getRequest();

        $editForm->bindRequest($request);

        if ($editForm->isValid()) {
            
            $equipo = $em->getRepository('INHack20EquipoBundle:Equipo')->find($equipo_id);

                if (!$equipo) {
                    throw $this->createNotFoundException('Unable to find Equipo entity.');
                }
            
            $em->persist($entity);
            $em->flush();
            if($request->isXmlHttpRequest()){
                if($equipo_id > 0)
                {
                    return $this->render('INHack20EquipoBundle:Componente:lista.html.twig', array('entities' => $equipo->getComponentes(), 'equipo_id' => $equipo_id ));
                }
                else
                    return $this->forward('INHack20EquipoBundle:Componente:show', array('id' => $entity->getId()));
             }
             else
                 return $this->redirect($this->generateUrl('componente_show', array('id' => $entity->getId())));
        }

        return array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        );
    }

    /**
     * Deletes a Componente entity.
     *
     * @Route("/{equipo_id}/{id}/delete", name="componente_delete", requirements={"equipo_id" = "\d+"}, defaults={"equipo_id"="0"})
     * @Method("post")
     */
    public function deleteAction($equipo_id,$id)
    {
        $form = $this->createDeleteForm($id);
        $request = $this->getRequest();

        $form->bindRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getEntityManager();
            $entity = $em->getRepository('INHack20EquipoBundle:Componente')->find($id);

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find Componente entity.');
            }
            
            $equipo = $em->getRepository('INHack20EquipoBundle:Equipo')->find($equipo_id);

                if (!$equipo) {
                    throw $this->createNotFoundException('Unable to find Equipo entity.');
                }
            
            $em->remove($entity);
            $em->flush();
            
                if($equipo_id > 0)
                {
                    return $this->render('INHack20EquipoBundle:Componente:lista.html.twig', array('entities' => $equipo->getComponentes(), 'equipo_id' => $equipo_id ));
                }
        }
        if($request->isXmlHttpRequest())
            return $this->forward('INHack20EquipoBundle:Componente:index');
        else
            return $this->redirect($this->generateUrl('componente'));
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', 'hidden')
            ->getForm()
        ;
    }
}
