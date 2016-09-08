<?php

namespace SRQ\UserBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Sucursal
 *
 * @ORM\Table(name="sucursales")
 * @ORM\Entity(repositoryClass="SRQ\UserBundle\Repository\SucursalRepository")
 */
class Sucursal
{
    /**
     * @ORM\OneToMany(targetEntity="User", mappedBy="user")
     */ 
    protected $sucursales;
    
    
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="namesuc", type="string", length=100)
     */
    private $namesuc;

    /**
     * @var string
     *
     * @ORM\Column(name="addresssuc", type="string", length=150)
     */
    private $addresssuc;

    /**
     * @var bool
     *
     * @ORM\Column(name="statussuc", type="boolean")
     */
    private $statussuc;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime")
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime")
     */
    private $updatedAt;


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
     * Set namesuc
     *
     * @param string $namesuc
     * @return Sucursal
     */
    public function setNamesuc($namesuc)
    {
        $this->namesuc = $namesuc;

        return $this;
    }

    /**
     * Get namesuc
     *
     * @return string 
     */
    public function getNamesuc()
    {
        return $this->namesuc;
    }

    /**
     * Set addresssuc
     *
     * @param string $addresssuc
     * @return Sucursal
     */
    public function setAddresssuc($addresssuc)
    {
        $this->addresssuc = $addresssuc;

        return $this;
    }

    /**
     * Get addresssuc
     *
     * @return string 
     */
    public function getAddresssuc()
    {
        return $this->addresssuc;
    }

    /**
     * Set statussuc
     *
     * @param boolean $statussuc
     * @return Sucursal
     */
    public function setStatussuc($statussuc)
    {
        $this->statussuc = $statussuc;

        return $this;
    }

    /**
     * Get statussuc
     *
     * @return boolean 
     */
    public function getStatussuc()
    {
        return $this->statussuc;
    }

    /**
     * Set createdAt
     *
     * @param \DateTime $createdAt
     * @return Sucursal
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get createdAt
     *
     * @return \DateTime 
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set updatedAt
     *
     * @param \DateTime $updatedAt
     * @return Sucursal
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * Get updatedAt
     *
     * @return \DateTime 
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->sucursales = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add sucursales
     *
     * @param \SRQ\UserBundle\Entity\User $sucursales
     * @return Sucursal
     */
    public function addSucursale(\SRQ\UserBundle\Entity\User $sucursales)
    {
        $this->sucursales[] = $sucursales;

        return $this;
    }

    /**
     * Remove sucursales
     *
     * @param \SRQ\UserBundle\Entity\User $sucursales
     */
    public function removeSucursale(\SRQ\UserBundle\Entity\User $sucursales)
    {
        $this->sucursales->removeElement($sucursales);
    }

    /**
     * Get sucursales
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getSucursales()
    {
        return $this->sucursales;
    }
}
