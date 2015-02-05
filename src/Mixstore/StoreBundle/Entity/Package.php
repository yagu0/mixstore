<?php

namespace Mixstore\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Package
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mixstore\StoreBundle\Entity\PackageRepository")
 */
class Package
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
	 * @ORM\Column(type="datetime")
	 * @Assert\NotBlank()
	 */
	private $created;

	/**
	 * @ORM\Column(type="datetime")
	 * @Assert\NotBlank()
	 */
	private $modified;

	/**
	 * @ORM\Column(type="string", unique=true)
	 * @Assert\NotBlank()
	 */
	private $name;

	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	private $headline;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 * @Assert\Url()
	 */
	private $url;
	
	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $description;

	/**
	 * @ORM\Column(type="text", nullable=true)
	 */
	private $dependencies;
	
	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	private $authors;
	
	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $contact; //un email
	
	/**
	* @ORM\ManyToOne(targetEntity="Mixstore\UserBundle\Entity\User")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $user; //owner of the package: "webmaster"...

	/**
	* @ORM\ManyToOne(targetEntity="Mixstore\StoreBundle\Entity\Language")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $language;

	/**
	 * @ORM\Column(type="string", nullable=true)
	 */
	private $bannerpath; //chemin vers banner image

	// ---
	//quick ugly way: TOFIX
	public $bannerfile;
	public $removebanner;
	// ---

	public function __construct()
	{
		$this->user = null;
		$this->id = 0;
		$this->created = new \DateTime('now');
		$this->modified = new \DateTime('now');
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
	
	//un package a un "webmaster ID" (masqué sur la page du package, mais permettant de savoir qui a le droit de l'éditer)

	/**
	 * Set name
	 *
	 * @param string $name
	 * @return Package
	 */
	public function setName($name)
	{
		$this->name = $name;

		return $this;
	}

	/**
	 * Get name
	 *
	 * @return string 
	 */
	public function getName()
	{
		return $this->name;
	}

	/**
	 * Set url
	 *
	 * @param string $url
	 * @return Package
	 */
	public function setUrl($url)
	{
		$this->url = $url;

		return $this;
	}

	/**
	 * Get url
	 *
	 * @return string 
	 */
	public function getUrl()
	{
		return $this->url;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Package
	 */
	public function setDescription($description)
	{
		$this->description = $description;

		return $this;
	}

	/**
	 * Get description
	 *
	 * @return string 
	 */
	public function getDescription()
	{
		return $this->description;
	}

	/**
	 * Set authors
	 *
	 * @param string $authors
	 * @return Package
	 */
	public function setAuthors($authors)
	{
		$this->authors = $authors;

		return $this;
	}

	/**
	 * Get authors
	 *
	 * @return string 
	 */
	public function getAuthors()
	{
		return $this->authors;
	}

	/**
	 * Set contact
	 *
	 * @param string $contact
	 * @return Package
	 */
	public function setContact($contact)
	{
		$this->contact = $contact;

		return $this;
	}

	/**
	 * Get contact
	 *
	 * @return string 
	 */
	public function getContact()
	{
		return $this->contact;
	}

	/**
	 * Set user
	 *
	 * @param \Mixstore\UserBundle\Entity\User $user
	 * @return Package
	 */
	public function setUser(\Mixstore\UserBundle\Entity\User $user)
	{
		$this->user = $user;

		return $this;
	}

	/**
	 * Get user
	 *
	 * @return \Mixstore\UserBundle\Entity\User 
	 */
	public function getUser()
	{
		return $this->user;
	}

    /**
     * Set headline
     *
     * @param string $headline
     * @return Package
     */
    public function setHeadline($headline)
    {
        $this->headline = $headline;

        return $this;
    }

    /**
     * Get headline
     *
     * @return string 
     */
    public function getHeadline()
    {
        return $this->headline;
    }

    /**
     * Set bannerpath
     *
     * @param string $bannerpath
     * @return Package
     */
    public function setBannerpath($bannerpath)
    {
        $this->bannerpath = $bannerpath;

        return $this;
    }

    /**
     * Get bannerpath
     *
     * @return string 
     */
    public function getBannerpath()
    {
        return $this->bannerpath;
    }

    /**
     * Set modified
     *
     * @param \DateTime $modified
     * @return Package
     */
    public function setModified($modified)
    {
        $this->modified = $modified;

        return $this;
    }

    /**
     * Get modified
     *
     * @return \DateTime 
     */
    public function getModified()
    {
        return $this->modified;
    }

    /**
     * Set language
     *
     * @param \Mixstore\StoreBundle\Entity\Language $language
     * @return Package
     */
    public function setLanguage(\Mixstore\StoreBundle\Entity\Language $language)
    {
        $this->language = $language;

        return $this;
    }

    /**
     * Get language
     *
     * @return \Mixstore\StoreBundle\Entity\Language 
     */
    public function getLanguage()
    {
        return $this->language;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Package
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set dependencies
     *
     * @param string $dependencies
     * @return Package
     */
    public function setDependencies($dependencies)
    {
        $this->dependencies = $dependencies;

        return $this;
    }

    /**
     * Get dependencies
     *
     * @return string 
     */
    public function getDependencies()
    {
        return $this->dependencies;
    }
}
