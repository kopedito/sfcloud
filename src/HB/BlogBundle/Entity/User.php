<?php

namespace HB\BlogBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * User
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class User
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
     * @ORM\Column(name="username", type="string", length=255)
     */
    private $username;

    /**
     * @var string
     *
     * @ORM\Column(name="login", type="string", length=255)
     */
    private $login;

    /**
     * @var string
     *
     * @ORM\Column(name="password", type="string", length=255)
     */
    private $password;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="datecre", type="datetime")
     */
    private $datecre;

    /**
     * @var boolean
     *
     * @ORM\Column(name="isadmin", type="boolean")
     */
    private $isadmin;

    /**
     * @var \stdClass
     *
     * @ORM\Column(name="picture", type="object", nullable=true)
     */
    private $picture;

    
    /**
     * @var Collection
     * 
     * @ORM\OneToMany(targetEntity="HB\BlogBundle\Entity\Article", mappedBy="auteur")
     */
    private $articles;

    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->articles = new \Doctrine\Common\Collections\ArrayCollection();
        $this->datecre = new \DateTime();
        $this->isadmin = false;
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
     * Set username
     *
     * @param string $username
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Get username
     *
     * @return string 
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set login
     *
     * @param string $login
     * @return User
     */
    public function setLogin($login)
    {
        $this->login = $login;

        return $this;
    }

    /**
     * Get login
     *
     * @return string 
     */
    public function getLogin()
    {
        return $this->login;
    }

    /**
     * Set password
     *
     * @param string $password
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Get password
     *
     * @return string 
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set datecre
     *
     * @param \DateTime $datecre
     * @return User
     */
    public function setDatecre($datecre)
    {
        $this->datecre = $datecre;

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
     * Set isadmin
     *
     * @param boolean $isadmin
     * @return User
     */
    public function setIsadmin($isadmin)
    {
        $this->isadmin = $isadmin;

        return $this;
    }

    /**
     * Get isadmin
     *
     * @return boolean 
     */
    public function getIsadmin()
    {
        return $this->isadmin;
    }

    /**
     * Set picture
     *
     * @param \stdClass $picture
     * @return User
     */
    public function setPicture($picture)
    {
        $this->picture = $picture;

        return $this;
    }

    /**
     * Get picture
     *
     * @return \stdClass 
     */
    public function getPicture()
    {
        return $this->picture;
    }
    

    /**
     * Add articles
     *
     * @param \HB\BlogBundle\Entity\Article $article
     * @return User
     */
    public function addArticle(\HB\BlogBundle\Entity\Article $article)
    {
        
        //on aurait pu faire un $article->setAuteur($this)
        //il faut le faire d'un coté ou de l'autre, mais pas les deux
        $this->articles[] = $article;

        return $this;
    }

    /**
     * Remove articles
     *
     * @param \HB\BlogBundle\Entity\Article $articles
     */
    public function removeArticle(\HB\BlogBundle\Entity\Article $articles)
    {
        $this->articles->removeElement($articles);
    }

    /**
     * Get articles
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getArticles()
    {
        return $this->articles;
    }
}
