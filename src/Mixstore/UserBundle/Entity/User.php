<?php

namespace Mixstore\UserBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity()
 * @ORM\Table(name="ms_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;
    
    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     */
    private $surname;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emailnotif0 = false;

    /**
     * @ORM\Column(type="boolean")
     */
    private $emailnotif1 = false;
    
    public function __construct()
    {
        parent::__construct();
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
     * Set name
     *
     * @param string $name
     * @return User
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
     * Set surname
     *
     * @param string $surname
     * @return User
     */
    public function setSurname($surname)
    {
        $this->surname = $surname;

        return $this;
    }

    /**
     * Get surname
     *
     * @return string 
     */
    public function getSurname()
    {
        return $this->surname;
    }
    
    /**
     * Set email (override base behavior)
     *
     * @param string $email
     * @return User
     */
    public function setEmail($email)
	{
		//http://stackoverflow.com/questions/8832916/remove-replace-the-username-field-with-email-using-fosuserbundle-in-symfony2
		$email = is_null($email) ? '' : $email;
		parent::setEmail($email);
		$this->setUsername($email);
		
		return $this;
	}

    /**
     * Set emailnotif0
     *
     * @param boolean $emailnotif0
     * @return User
     */
    public function setEmailnotif0($emailnotif0)
    {
        $this->emailnotif0 = $emailnotif0;

        return $this;
    }

    /**
     * Get emailnotif0
     *
     * @return boolean 
     */
    public function getEmailnotif0()
    {
        return $this->emailnotif0;
    }

    /**
     * Set emailnotif1
     *
     * @param boolean $emailnotif1
     * @return User
     */
    public function setEmailnotif1($emailnotif1)
    {
        $this->emailnotif1 = $emailnotif1;

        return $this;
    }

    /**
     * Get emailnotif1
     *
     * @return boolean 
     */
    public function getEmailnotif1()
    {
        return $this->emailnotif1;
    }
}
