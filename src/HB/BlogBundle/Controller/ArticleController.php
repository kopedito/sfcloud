<?php

namespace HB\BlogBundle\Controller;

use HB\BlogBundle\Entity\Article;
use HB\BlogBundle\Entity\UserRepository;
use HB\BlogBundle\Form\ArticleType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

//use BeSimple\SoapBundle\ServiceDefinition\Annotation as Soap;
//use Symfony\Component\DependencyInjection\ContainerAware;

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

    private function editArticle($article) 
    //!!! editArticle(Article $article) n'accepte pas null en param, meme si find() peut retourner null
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
        return array('form_article' => $form->createView() );
    }

    /**
     * Ajoute un article
     * @Route("/add" , name="hb_blog_article_add")
     * @Template()
     */
    public function addAction()
    {
        return $this->editArticle(new Article());
    }
    
    /**
     * Liste tous les articles
     * 
     * @Route("/" , name="hb_blog_article_index")
     * @Route("/user/{uid}" , name="hb_blog_article_user_index")
     * @Template()
     */
    public function indexAction($uid = 0)
    {
        if ($uid == 0)
        {
            $articles = $this->getArticleRepository()->findAll();
        }
        else
        {
            $user = $this->getDoctrine()->getRepository("HBBlogBundle:User")->find($uid);
            $articles = $user->getArticles();
            //plus rapide:
            //$articles = $this->getArticleRepository()->findBy(array('auteur'=>$uid));
        }
        return array('articles'=> $articles , 'user' => null);
    }

    /**
     * Affiche un article par son id
     * @Route("/read/{id}" , name="hb_blog_article_read")
     * @Template()
     */
    public function readAction($id)          //(Article $article) !!ParamConvereter renvoie une exception si l'article n'existe pas
    {
        return array ('article' => $this->getArticleRepository()->find($id));
    }
    
    /**
     * Modifie un article par son id
     * @Route("/mod/{id}" , name="hb_blog_article_mod")
     * @Template()
     */
    //@Route("/titre/{titre}/mod")
    //!!! ParamConverter renvoie une exception si l'article n'est pas trouvé...
    public function modAction($id)
    {
        $article = $this->getArticleRepository()->find($id);
        return $this->editArticle($article);
    }

    /**
     * Delete un article par son id
     * @Route("/del/{id}" , name="hb_blog_article_del")
     * @Template()
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
        
        return array('id' => $id, 'ok' => $ok);
    }
}
