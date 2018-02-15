<?php
// src/AppBundle/Entity/User.php

namespace AppBundle\Entity;

use FOS\UserBundle\Model\User as BaseUser;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="fos_user")
 */
class User extends BaseUser
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="facebook_id", type="string", length=255, nullable=true) */
    protected $facebook_id;

    /** @ORM\Column(name="facebook_access_token", type="string", length=255, nullable=true) */
   protected $facebook_access_token;

   /** @ORM\Column(name="google_id", type="string", length=255, nullable=true) */
   protected $google_id;

   /** @ORM\Column(name="google_access_token", type="string", length=255, nullable=true) */
   protected $google_access_token;
   
    /**
     * @ORM\OneToOne(targetEntity="Address", mappedBy="user_address")
     */
    private $address;

    /**
     * @ORM\OneToMany(targetEntity="Suscritors", mappedBy="user_suscritor")
     */
    private $suscritor;

    public function __construct()
    {
        parent::__construct();
        // your own logic
    }

    public function setFacebookId($facebook_id){
      $this->facebook_id = $facebook_id;
    }

    public function setFacebookAccessToken($access_token){
      $this->facebook_access_token = $access_token;
    }

    public function setGoogleId($google_id){
      $this->google_id = $google_id;
    }

    public function setGoogleAccessToken($access_token){
      $this->google_access_token = $access_token;
    }

    /**
     * Get facebookId
     *
     * @return string
     */
    public function getFacebookId()
    {
        return $this->facebook_id;
    }

    /**
     * Get facebookAccessToken
     *
     * @return string
     */
    public function getFacebookAccessToken()
    {
        return $this->facebook_access_token;
    }

    /**
     * Get googleId
     *
     * @return string
     */
    public function getGoogleId()
    {
        return $this->google_id;
    }

    /**
     * Get googleAccessToken
     *
     * @return string
     */
    public function getGoogleAccessToken()
    {
        return $this->google_access_token;
    }

    /**
     * Set address
     *
     * @param \AppBundle\Entity\Address $address
     *
     * @return User
     */
    public function setAddress(\AppBundle\Entity\Address $address = null)
    {
        $this->address = $address;

        return $this;
    }

    /**
     * Get address
     *
     * @return \AppBundle\Entity\Address
     */
    public function getAddress()
    {
        return $this->address;
    }

    /**
     * @return mixed
     */
    public function getSuscritor()
    {
        return $this->suscritor;
    }

    /**
     * @param mixed $suscritor
     */
    public function setSuscritor($suscritor)
    {
        $this->suscritor = $suscritor;
    }
}
