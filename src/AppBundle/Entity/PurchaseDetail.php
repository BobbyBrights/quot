<?php
// src/AppBundle/Entity/Purchase.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="purchases_detail")
 */
class PurchaseDetail
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="user_id", type="string", length=255, nullable=true) */
    protected $user_id;
    
    /** @ORM\Column(name="title", type="string", length=255, nullable=true) */
    protected $title;
    
    /** @ORM\Column(name="description", type="string", length=255, nullable=true) */
    protected $description;
    
    /** @ORM\Column(name="value", type="integer", length=255, nullable=true) */
    protected $value;
    
    /** @ORM\Column(name="image", type="string", length=255, nullable=true) */
    protected $image;
    
    /** @ORM\Column(name="size", type="string", length=255, nullable=true) */
    protected $size;
    
    /** @ORM\Column(name="vid", type="integer", length=255, nullable=true) */
    protected $vid;

    /** @ORM\Column(name="vidEdit", type="integer", length=255, nullable=true) */
    protected $vidEdit;

    /** @ORM\Column(name="vid_parent", type="integer", length=255, nullable=true) */
    protected $vid_parent;
    
    /** @ORM\Column(name="quant", type="integer", length=255, nullable=true) */
    protected $quant;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status;
    
    /**
     * @ORM\ManyToOne(targetEntity="Purchase", inversedBy="purchase")
     * @ORM\JoinColumn(name="purchases_details", referencedColumnName="id", nullable=TRUE)
     **/
    protected $purchase_detail;

    /** @ORM\Column(name="user_anonimo", type="string", length=255, nullable=true) */
    protected $user_anonimo;

    /** @ORM\Column(name="shirt_thum", type="string", length=255, nullable=true) */
    protected $shirt_thum;

    /** @ORM\Column(name="texts", type="string", length=1000, nullable=true) */
    protected $texts;

    /** @ORM\Column(name="img_btn", type="string", length=255, nullable=true) */
    protected $img_btn;

    /** @ORM\Column(name="combinations", type="string", length=255, nullable=true) */
    protected $combinations;

    /** @ORM\Column(name="custom", type="integer", length=255, nullable=true) */
    protected $custom;

    /**
     * @return mixed
     */
    public function getImgBtn()
    {
        return $this->img_btn;
    }

    /**
     * @param mixed $img_btn
     */
    public function setImgBtn($img_btn)
    {
        $this->img_btn = $img_btn;
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
     * Set userId
     *
     * @param string $userId
     *
     * @return PurchaseDetail
     */
    public function setUserId($userId)
    {
        $this->user_id = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return string
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * Set title
     *
     * @param string $title
     *
     * @return PurchaseDetail
     */
    public function setTitle($title)
    {
        $this->title = $title;

        return $this;
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set description
     *
     * @param string $description
     *
     * @return PurchaseDetail
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
     * Set value
     *
     * @param integer $value
     *
     * @return PurchaseDetail
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return integer
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set image
     *
     * @param string $image
     *
     * @return PurchaseDetail
     */
    public function setImage($image)
    {
        $this->image = $image;

        return $this;
    }

    /**
     * Get image
     *
     * @return string
     */
    public function getImage()
    {
        return $this->image;
    }

    /**
     * Set size
     *
     * @param string $size
     *
     * @return PurchaseDetail
     */
    public function setSize($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * Get size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->size;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return PurchaseDetail
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return integer
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set purchaseDetail
     *
     * @param \AppBundle\Entity\Purchase $purchaseDetail
     *
     * @return PurchaseDetail
     */
    public function setPurchaseDetail(\AppBundle\Entity\Purchase $purchaseDetail = null)
    {
        $this->purchase_detail = $purchaseDetail;

        return $this;
    }

    /**
     * Get purchaseDetail
     *
     * @return \AppBundle\Entity\Purchase
     */
    public function getPurchaseDetail()
    {
        return $this->purchase_detail;
    }

    /**
     * Set vid
     *
     * @param integer $vid
     *
     * @return PurchaseDetail
     */
    public function setVid($vid)
    {
        $this->vid = $vid;

        return $this;
    }

    /**
     * Get vid
     *
     * @return integer
     */
    public function getVid()
    {
        return $this->vid;
    }

    /**
     * Set quant
     *
     * @param integer $quant
     *
     * @return PurchaseDetail
     */
    public function setQuant($quant)
    {
        $this->quant = $quant;

        return $this;
    }

    /**
     * Get quant
     *
     * @return integer
     */
    public function getQuant()
    {
        return $this->quant;
    }

    /**
     * Set vidParent
     *
     * @param integer $vidParent
     *
     * @return PurchaseDetail
     */
    public function setVidParent($vidParent)
    {
        $this->vid_parent = $vidParent;

        return $this;
    }

    /**
     * Get vidParent
     *
     * @return integer
     */
    public function getVidParent()
    {
        return $this->vid_parent;
    }

    /**
     * Set userAnonimo
     *
     * @param string $userAnonimo
     *
     * @return PurchaseDetail
     */
    public function setUserAnonimo($userAnonimo)
    {
        $this->user_anonimo = $userAnonimo;

        return $this;
    }

    /**
     * Get userAnonimo
     *
     * @return string
     */
    public function getUserAnonimo()
    {
        return $this->user_anonimo;
    }

    /**
     * Set shirtThum
     *
     * @param string $shirtThum
     *
     * @return PurchaseDetail
     */
    public function setShirtThum($shirtThum)
    {
        $this->shirt_thum = $shirtThum;

        return $this;
    }

    /**
     * Get shirtThum
     *
     * @return string
     */
    public function getShirtThum()
    {
        return $this->shirt_thum;
    }

    /**
     * Set texts
     *
     * @param string $texts
     *
     * @return PurchaseDetail
     */
    public function setTexts($texts)
    {
        $this->texts = $texts;

        return $this;
    }

    /**
     * Get texts
     *
     * @return string
     */
    public function getTexts()
    {
        return $this->texts;
    }

    /**
     * @return mixed
     */
    public function getVidEdit()
    {
        return $this->vidEdit;
    }

    /**
     * @param mixed $vidEdit
     */
    public function setVidEdit($vidEdit)
    {
        $this->vidEdit = $vidEdit;
    }

    /**
     * @return mixed
     */
    public function getCombinations()
    {
        return $this->combinations;
    }

    /**
     * @param mixed $combinations
     */
    public function setCombinations($combinations)
    {
        $this->combinations = $combinations;
    }

    /**
     * @return mixed
     */
    public function getCustom()
    {
        return $this->custom;
    }

    /**
     * @param mixed $custom
     */
    public function setCustom($custom)
    {
        $this->custom = $custom;
    }


}
