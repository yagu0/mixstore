<?php

namespace Mixstore\StoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Language
 *
 * @ORM\Table()
 * @ORM\Entity
 */
class Language
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
     * @ORM\Column(name="name", type="string", length=32, unique=true)
     */
    private $name;

	public function __construct($name)
	{
		$this->name = $name;
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
     * @return Language
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
}

/*
* current languages
C, C#, C++, Java, Julia, Python, R, Fortran, Lua, Matlab, Octave, Scilab
* others
Javascript,Objective-C,Perl, PHP,Ruby,Shell,Scala, Go,Groovy,Ada, Android,Boo, Clojure, Common Lisp, Delphi, Eiffel, Erlang, Fantom,Haskell,Node.js, OCaml,Prolog
*/
