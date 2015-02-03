<?php

namespace HB\BlogBundle\Controller;

use HB\BlogBundle\Entity\User;
use HB\BlogBundle\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controleur de l'entité user 
 * @Route("/user")
 */
class UserController extends Controller
{
       
    private function getUserRepository()
    {
        return $this->getDoctrine()->getRepository("HBBlogBundle:User");
    }

    private function editUser($user) 
    {
          if ($user == null) //mod d'un user qui n'existe pas
            {
                return array();
            }
        
        $form = $this->createForm(new UserType, $user);//$formBuilder->getForm();

        $request = $this->get('request');
        if ($request->getMethod() == 'POST') {
            $form->bind($request);
            if ($form->isValid()) {
                $em = $this->getDoctrine()->getManager();
                $em->persist($user);
                $em->flush();
                return $this->redirect($this->generateUrl('hb_blog_user_read', array('id' => $user->getId())));
            }
        } 
        return array('form_user' => $form->createView() );
    }

    /**
     * Ajoute un user
     * @Route("/login")
     * @Template()
     */
    public function loginAction()
    {
        return $this->editUser(new User());
    }
    
    /**
     * Ajoute un user
     * @Route("/add")
     * @Template()
     */
    public function addAction()
    {
        return $this->editUser(new User());
    }
    
    /**
     * Liste tous les users
     * 
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $users = $this->getUserRepository()->findAll();
        return array('users'=> $users);
    }

    /**
     * Affiche un user par son id
     * @Route("/{id}")
     * @Template()
     */
    public function readAction($id)          //(User $user) !!ParamConvereter renvoie une exception si l'user n'existe pas
    {
        return array ('user' => $this->getUserRepository()->find($id));
    }
    
    /**
     * Modifie un user par son id
     * @Route("/{id}/mod" , name="user_mod")
     * @Template()
     */
    public function modAction($id)
    {
        $user = $this->getUserRepository()->find($id);
        return $this->editUser($user);
    }

    /**
     * Delete un user par son id
     * @Route("/{id}/del")
     * @Template()
     */
    public function delAction($id)  //sans paramconverter qui retourne une erreur si la table n'existe pas!!!
    {
        $user = $this->getUserRepository()->find($id);
        $user->detachArticles();
        $ok = false;
        if ($user != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
            $ok = true;
        }
        
        return array('id' => $id, 'ok' => $ok);
    }
}
