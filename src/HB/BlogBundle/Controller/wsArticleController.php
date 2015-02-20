<?php

namespace HB\BlogBundle\Controller;

use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
use Symfony\Component\DependencyInjection\ContainerAware;
use HB\BlogBundle\Entity\Article;
use HB\BlogBundle\Entity\ArticleRepository;
use HB\BlogBundle\Entity\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

/**
 * WS Controleur de l'entité article 
 */

class wsArticleController extends Controller
{    
    private function getArticleRepository()
    {
        return $this->getDoctrine()->getRepository("HBBlogBundle:Article");
    }
    private function getUserRepository()
    {
        return $this->getDoctrine()->getRepository("HBBlogBundle:User");
    }

    private function editArticle(Article $article) 
    {
        if ($article == null) //mod d'un article qui n'existe pas
        {
            throw new \SoapFault("Sender", "Invalid data");
            return $article;
        }
        
        $em = $this->getDoctrine()->getManager();
        $article->setDatemod(new \DateTime());
        if ($article->getId()<=0)       //ADD
        {
            $article->setDatecre(new \DateTime());
        }
        else                            //MOD
        {
            $oldArticle = $em->find("HBBlogBundle:Article", $article->getId());
            $article->setDateCre($oldArticle->getDateCre());            
        }
        
        // on utilise merge et non persist puisque notre objet Article ne vient
        // pas de l'entitymanager mais est instancié à partir de SoapBundle
        $em->merge($article);
        $em->flush();
        return $article;
    }

    /**
     * Ajoute un article
     * @Soap\Method("addArticle")
     * @Soap\Param("article", phpType = "HB\BlogBundle\Entity\Article")
     * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article")
     */
    public function addAction($article)
    {
        return $this->editArticle($article);
    }
    
    /**
     * Liste tous les articles
     * 
     * @Soap\Method("listArticle")
     * @Soap\Param("titre", phpType = "int")
     * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article[]")
    */
    public function listAction($uid = 0)
    {
        if ($uid == 0)
        {
            $articles = $this->getArticleRepository()->findAll();
        }
        else
        {
            $articles = $this->getArticleRepository()->findBy(array('auteur'=>$uid));
        }
        return $articles;
    }

    /**
     * Affiche un article par son id
     * @Soap\Method("readArticle")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article")
     */
    public function readAction($id)
    {
        return $this->getArticleRepository()->find($id);
    }
    
    /**
     * Modifie un article
     * @Soap\Method("modArticle")
     * @Soap\Param("article", phpType = "HB\BlogBundle\Entity\Article")
     * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article")    */
    public function modAction($article)
    {
//        $article = $this->getArticleRepository()->find($article->id);
        return $this->editArticle($article);
    }

    /**
     * Delete un article par son id
     * @Soap\Method("delArticle")
     * @Soap\Param("id", phpType = "int")
     * @Soap\Result(phpType = "boolean")     
     */
    public function delAction($id)
    {
        $article = $this->getArticleRepository()->find($id);
        $ok = false;
        if ($article != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            $ok = true;
        }
        
        return $ok;
    }
}
