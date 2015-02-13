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

    private function editArticle($article) 
    {
          if ($article == null) //mod d'un article qui n'existe pas
            {
                //erreur : rediriger vers une page d'erreur avec un msg i.e. err.html.twig
                //return $this->redirect($this->generateUrl('hb_blog_article_index'));
                //ou tester l'existence du formulaire dans le twig...
              return array();
            }
            else if ($article->getId()>0)   //mod d'un article connu
            {
                $article->setDatemod(new \DateTime());
            }
        
/*
        // On vérifie qu'elle est de type POST pour voir si un formulaire a été soumis
        if ($request->getMethod() == 'POST') {
            // On fait le lien Requête <-> Formulaire
            // À partir de maintenant, la variable $article contient les valeurs entrées dans 
            // le formulaire par le visiteur
            $form->bind($request);
            // On vérifie que les valeurs entrées sont correctes
            // (Nous verrons la validation des objets en détail dans le prochain chapitre)
            if ($form->isValid()) {
                // On l'enregistre notre objet $article dans la base de données
                $em = $this->getDoctrine()->getManager();
                $em->persist($article);
                $em->flush();

                // On redirige vers la page de visualisation de l'article nouvellement créé
                //la liste des routes s'obtient avec la commande:
                // php app/console route:debug
                return $this->redirect($this->generateUrl('hb_blog_article_read', array('id' => $article->getId())));
            }
        } 
*/
    }

    /**
     * Ajoute un article
     */
    public function addAction()
    {
        return $this->editArticle(new Article());
    }
    
    /**
     * Liste tous les articles
     * 
     * @Soap\Method("index")
     * @Soap\Param("uid", phpType = "int")
     * @Soap\Result(phpType = "HB\BlogBundle\Entity\Article")
    */
    public function indexAction($uid = 0)
    {
        if ($uid == 0)
        {
            $articles = $this->getArticleRepository()->findAll();
        }
        else
        {
            $user = $this->getUserRepository()->find($uid);
            $articles = $user->getArticles();
            //plus rapide:
            //$articles = $this->getArticleRepository()->findBy(array('auteur'=>$uid));
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
     * Modifie un article par son id
     */
    public function modAction($id)
    {
        $article = $this->getArticleRepository()->find($id);
        return $this->editArticle($article);
    }

    /**
     * Delete un article par son id
     */
    public function delAction($id)  //sans paramconverter qui retourne une erreur si la table n'existe pas!!!
    {
        $article = $this->getArticleRepository()->find($id);
        $ok = false;
        if ($article != null) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($article);
            $em->flush();
            $ok = true;
        }
        
        return "ok";
    }
}
