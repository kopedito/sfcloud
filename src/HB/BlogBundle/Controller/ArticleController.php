<?php

namespace HB\BlogBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * Controleur de l'entité article 
 * @Route("/article")
 */
class ArticleController extends Controller
{     
   
    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository("HBBlogBundle:Article");
    }

    /**
     * Ajoute un article
     * @Route("/add")
     * @Template()
     */
    public function addAction()
    {
        return array();
    }
 
    /**
     * Liste tous les articles
     * 
     * @Route("/")
     * @Template()
     */
    public function indexAction()
    {
        $articles = $this->getArticleRepository()->findAll();
         return array('articles'=> $articles);
    }

    /**
     * Affiche un article par son id
     * @Route("/{id}")
     * @Template()
     */
    public function readAction($id)
    {
        $article = $this->getArticleRepository()->find($id);
        
        return array ('article' => $article);
    }

    /**
     * Modifie un article par son id
     * @Route("/{id}/mod")
     * @Template()
     */
    public function modAction($id)
    {
        return array('id' => $id);
    }

    /**
     * Delete un article par son id
     * @Route("/{id}/del")
     * @Template()
     */
    public function delAction($id)
    {
        return array('id' => $id);
    }

           
    
}
