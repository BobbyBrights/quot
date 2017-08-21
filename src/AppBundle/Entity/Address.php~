<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="address")
 */
class Address
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="name", type="string", length=255, nullable=true) */
    protected $name;

    /** @ORM\Column(name="address", type="string", length=255, nullable=true) */
   protected $address;

   /** @ORM\Column(name="city", type="string", length=255, nullable=true) */
   protected $city;

   /** @ORM\Column(name="state", type="string", length=255, nullable=true) */
   protected $state;
   
   /** @ORM\Column(name="phone", type="string", length=255, nullable=true) */
   protected $phone;
   
   /**
     * @ORM\OneToOne(targetEntity="User", inversedBy="address")
     * @ORM\JoinColumn(name="user_address", referencedColumnName="id", nullable=TRUE)
     */
    private $user_address;   

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
     *
     * @return Address
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
     * Set address
     *
     * @param string $address
     *
     * @return Address
     */
    public function setAddress($address)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return string
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * Set city
     *
     * @param string $city
     *
     * @return Address
     */
    public function setCity($city)
    {
        $this->city = $city;

        return $this;
    }

    /**
     * Get city
     *
     * @return string
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set state
     *
     * @param string $state
     *
     * @return Address
     */
    public function setState($state)
    {
        $this->state = $state;

        return $this;
    }

    /**
     * Get state
     *
     * @return string
     */
    public function getState()
    {
        return $this->state;
    }

    /**
     * Set phone
     *
     * @param string $phone
     *
     * @return Address
     */
    public function setPhone($phone)
    {
        $this->phone = $phone;

        return $this;
    }

    /**
     * Get phone
     *
     * @return string
     */
    public function getPhone()
    {
        return $this->phone;
    }

    /**
     * Set userAddress
     *
     * @param \AppBundle\Entity\User $userAddress
     *
     * @return Address
     */
    public function setUserAddress(\AppBundle\Entity\User $userAddress = null)
    {
        $this->user_address = $userAddress;

        return $this;
    }

    /**
     * Get userAddress
     *
     * @return \AppBundle\Entity\User
     */
    public function getUserAddress()
    {
        return $this->user_address;
    }
}
