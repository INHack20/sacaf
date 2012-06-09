<?php

namespace INHack20\UserBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
/**
 * Description of UsuarioController
 *
 * @author INHACK20
 */
class UsuarioController extends Controller{
    
    /**
     * Listar todos los usuarios 
     * @Route("/lista",name="fos_user_lista_usuarios")
     */
    public function listaAction(){
        $em = $this->getDoctrine()->getEntityManager();
        $usuarios = $em->getRepository('INHack20UserBundle:User')->findAll();
        return $this->render('INHack20UserBundle:Usuario:lista.html.twig',array(
            'usuarios' => $usuarios,
        ));
    }
    
    /**
     * Ver un usuario
     * 
     * @Route("/{id}/ver",name="fos_user_ver_usuario")
     */
    public function verAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usuario = $em->getRepository('INHack20UserBundle:User')->find($id);
        if(!$usuario){
            throw $this->createNotFoundException('No se ha encontrado el usuario especificado');
        }
        return $this->render('INHack20UserBundle:Usuario:show.html.twig',array(
            'usuario' => $usuario,
        ));
    }
    
    /**
     * Permite al ROLE_SUPER_ADMIN editar la informacion del usuario
     * 
     * @Route("/{id}/editar",name="fos_user_editar_usuario")
     */
    public function editar($id){
        $em = $this->getDoctrine()->getEntityManager();
        $usuario = $em->getRepository('INHack20UserBundle:User')->find($id);
        if(!$usuario){
            throw $this->createNotFoundException('No se ha encontrado el usuario especificado');
        }
        //$usuario->setRoleUser();
        $form = $this->createForm(new \INHack20\UserBundle\Form\UserType(),$usuario);
        
        return $this->render('FOSUserBundle:Usuario:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView(), 
                'theme' => $this->container->getParameter('fos_user.template.theme'),
                'usuario' => $usuario,
                )
                );
    }
    
    /**
     * Permite al ROLE_SUPER_ADMIN actualizar la informacion de cualquier usuario
     * @Route("/{id}/update",name="fos_user_update_usuario")
     */
    public function updateAction($id)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $usuario = $em->getRepository('INHack20UserBundle:User')->find($id);
        if(!$usuario){
            throw $this->createNotFoundException('No se ha encontrado el usuario especificado');
        }
        $request = $this->getRequest();
        $form = $this->createForm(new \INHack20\UserBundle\Form\UserType(),$usuario);
        $form->bindRequest($request);
            if($form->isValid())
            {
                $em->persist($usuario);
                $em->flush();
                return $this->redirect($this->generateUrl('fos_user_ver_usuario',array('id' => $usuario->getId())));
            }
            
         return $this->render('FOSUserBundle:Usuario:edit.html.'.$this->container->getParameter('fos_user.template.engine'),
            array('form' => $form->createView(), 
                'theme' => $this->container->getParameter('fos_user.template.theme'),
                'usuario' => $usuario,
                )
                );
    }
    
    /**
     * Permite a ROLE_SUPER_ADMIN cambiar la contraseña a un usuario
     * 
     * @Route("/{id}/change-password",name="fos_user_change_password_usuario")
     */
     public function changePasswordAction($id){
         
         $request= $this->getRequest();
         $usuario = $this->container->get('fos_user.user_manager')->findUserBy(array('id'=>$id));
         if (null === $usuario) {
            throw new NotFoundHttpException(sprintf('El usuario con "%s" no existe', $id));
        }
        
        $form= $this->createForm(new \INHack20\UserBundle\Form\ChangePasswordAdminType(),$usuario);
        
        if($request->getMethod()=='POST')
        {
            $form->bindRequest($request);
            
            if($form->isValid())
            {
                $this->container->get('fos_user.user_manager')->updatePassword($usuario);
                $this->container->get('fos_user.user_manager')->updateUser($usuario);
                return $this->redirect($this->generateUrl('fos_user_lista_usuarios'));
            }
        }
        return $this->render('INHack20UserBundle:ChangePassword:changePasswordAdmin.html.twig',array(
                'usuario' => $usuario,
                'form' => $form->createView(),
                'theme' => $this->container->getParameter('fos_user.template.theme'),
                ));
     }
}