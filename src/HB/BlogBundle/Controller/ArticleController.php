<?php

namespace HB\BlogBundle\Controller;

use HB\BlogBundle\Entity\Article;
use HB\BlogBundle\Form\ArticleType;
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

        private function editArticle($id=-1)
    {
        if ($id == -1) //add
        {
            $article = new Article();
        }
        else //mod
        {
            $article = $this->getArticleRepository()->find($id);
        }
        
        $form = $this->createForm(new ArticleType, $article);//$formBuilder->getForm();

        // On récupère la requête
        $request = $this->get('request');

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

        // On passe la méthode createView() du formulaire à la vue afin qu'elle puisse afficher 
        // le formulaire toute seule, on a d'autres méthodes si on veut personnaliser
        return array( 'form_article' => $form->createView() );
    }

    /**
     * Ajoute un article
     * @Route("/add")
     * @Template()
     */
    public function addAction()
    {
        return $this->editArticle();
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
    public function readAction(Article $article)  //utilisation du paramconverter
    {
        //$article = $this->getArticleRepository()->find($id);
        
        return array ('article' => $article);
    }
    
    /**
     * Modifie un article par son id
     * @Route("/{id}/mod")
     * @Template()
     */
    public function modAction($id)
    {
        return $this->editArticle($id);
    }

    /**
     * Delete un article par son id
     * @Route("/{id}/del")
     * @Template()
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
        
        return array('id' => $id, 'ok' => $ok);
    }

           
    
}
