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
    
    public function __construct()
    {
        $this->datecre = new \DateTime();//date("Y-m-d H:i:s");
        $this->datemod = new \DateTime();//date("Y-m-d H:i:s");
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
}
