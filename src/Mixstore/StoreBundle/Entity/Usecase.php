<?php

namespace Mixstore\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Usecase
 *
 * @ORM\Table()
 * @ORM\Entity(repositoryClass="Mixstore\StoreBundle\Entity\UsecaseRepository")
 */
class Usecase
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
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	private $institution;

	/**
	 * @ORM\Column(type="string")
	 * @Assert\NotBlank()
	 */
	private $headline; //titre du usecase
	
	/**
	 * @ORM\Column(type="text")
	 * @Assert\NotBlank()
	 */
	private $description;
	
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
	private $user; //owner of the usecase: "webmaster"...

	/**
	 * @ORM\Column(type="decimal")
	 * @Assert\NotBlank()
	 */
	private $grade; //from 0 to 10 (integer, in fact)

	/**
	* @ORM\ManyToOne(targetEntity="Mixstore\StoreBundle\Entity\Package")
	* @ORM\JoinColumn(nullable=false)
	*/
	private $package;

	public function __construct()
	{
		$this->user = null;
		$this->id = 0;
		$this->created = new \DateTime("now");
		$this->modified = new \DateTime("now");
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
	 * Set institution
	 *
	 * @param string $institution
	 * @return Usecase
	 */
	public function setInstitution($institution)
	{
		$this->institution = $institution;

		return $this;
	}

	/**
	 * Get institution
	 *
	 * @return string 
	 */
	public function getInstitution()
	{
		return $this->institution;
	}

	/**
	 * Set description
	 *
	 * @param string $description
	 * @return Usecase
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
	 * Set contact
	 *
	 * @param string $contact
	 * @return Usecase
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
	 * @return Usecase
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
     * @return Usecase
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
     * Set package
     *
     * @param \Mixstore\StoreBundle\Entity\Package $package
     * @return Usecase
     */
    public function setPackage(\Mixstore\StoreBundle\Entity\Package $package)
    {
        $this->package = $package;

        return $this;
    }

    /**
     * Get package
     *
     * @return \Mixstore\StoreBundle\Entity\Package 
     */
    public function getPackage()
    {
        return $this->package;
    }

    /**
     * Set grade
     *
     * @param boolean $happy
     * @return Usecase
     */
    public function setGrade($grade)
    {
        $this->grade = $grade;

        return $this;
    }

    /**
     * Get grade
     *
     * @return boolean 
     */
    public function getGrade()
    {
        return $this->grade;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Usecase
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
     * Set modified
     *
     * @param \DateTime $modified
     * @return Usecase
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
}
