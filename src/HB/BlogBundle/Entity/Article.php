<?php

namespace HB\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Article
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="HB\BlogBundle\Entity\ArticleRepository")
 */
class Article
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="titre", type="string", length=255)
     */
    private $titre;

    /**
     * @var string
     *
     * @ORM\Column(name="contenu", type="text", nullable=true)
     */
    private $contenu;

    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="datecre", type="datetime")
     */
    private $datecre;
    
    /**
     * @var \DateTime
     * 
     * @ORM\Column(name="datemod", type="datetime")
     */
    private $datemod;  
    //=> php app/console doctrine:generate:entities HBBlogBundle:Article
    //=> php app/console doctrine:schema:update --dump-sql
    //=> php app/console doctrine:schema:update --force
    
    /**
     * @ORM\ManyToOne(targetEntity="HB\BlogBundle\Entity\User", inversedBy="articles", cascade={"detach"})
     * @ORM\JoinColumn(name="auteur_id", referencedColumnName="id",  onDelete="SET NULL")
     */
    private $auteur;
    //relation inversée:
    // auteur (inversed) Mto1 Article->User  || articles (mapped) 1toM User->Article
    // => php app/console doctrine:schema:validate
    // aucun impact en DB, seule les clés étrangères (Mto1) existent en DB
    // les clés mapped (1toM) n'existent qu'en doctrine.
    
    /**
     * @var string
     *
     * @ORM\Column(name="auteur_nom", type="string", length=255)
     */
    private $auteur_nom;

    
    public function __construct()
    {
        $this->datecre = new \DateTime();//date("Y-m-d H:i:s");
        $this->datemod = new \DateTime();
    }
    
    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titre
     *
     * @param string $titre
     * @return Article
     */
    public function setTitre($titre)
    {
        $this->titre = $titre;

        return $this;
    }

    /**
     * Get titre
     *
     * @return string 
     */
    public function getTitre()
    {
        return $this->titre;
    }

    /**
     * Set contenu
     *
     * @param string $contenu
     * @return Article
     */
    public function setContenu($contenu)
    {
        $this->contenu = $contenu;

        return $this;
    }

    /**
     * Get contenu
     *
     * @return string 
     */
    public function getContenu()
    {
        return $this->contenu;
    }

    /**
     * Set datecre
     *
     * @param \DateTime $dateCre
     * @return Article
     */
    public function setDatecre($dateCre)
    {
        $this->datecre = $dateCre;

        return $this;
    }

    /**
     * Get datecre
     *
     * @return \DateTime 
     */
    public function getDatecre()
    {
        return $this->datecre;
    }

    /**
     * Set datemod
     *
     * @param \DateTime $datemod
     * @return Article
     */
    public function setDatemod($datemod)
    {
        $this->datemod = $datemod;

        return $this;
    }

    /**
     * Get datemod
     *
     * @return \DateTime 
     */
    public function getDatemod()
    {
        return $this->datemod;
    }

    /**
     * Set auteur_nom
     *
     * @param string $auteurNom
     * @return Article
     */
    public function setAuteurNom($auteurNom)
    {
        $this->auteur_nom = $auteurNom;

        return $this;
    }

    /**
     * Get auteur_nom
     *
     * @return string 
     */
    public function getAuteurNom()
    {
        return $this->auteur_nom;
    }

    /**
     * Set auteur
     *
     * @param \HB\BlogBundle\Entity\User $auteur
     * @return Article
     */
    public function setAuteur(\HB\BlogBundle\Entity\User $auteur = null)
    {
        
        $this->auteur = $auteur;
        if ($auteur != null)
        {
            $this->auteur_nom = $auteur->getUsername();
            $auteur->addArticle($this);
        }
        else
        {
            $this->auteur_nom = '';
        }
        return $this;
    }

    /**
     * Get auteur
     *
     * @return \HB\BlogBundle\Entity\User 
     */
    public function getAuteur()
    {
        return $this->auteur;
    }
}
