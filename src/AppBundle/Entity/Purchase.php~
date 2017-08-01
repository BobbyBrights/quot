<?php
// src/AppBundle/Entity/Purchase.php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="purchases")
 */
class Purchase
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /** @ORM\Column(name="user_id", type="string", length=255, nullable=true) */
    protected $user_id;

    /** @ORM\Column(name="value", type="string", length=255, nullable=true) */
    protected $value;
    
    /**
     * @ORM\OneToMany(targetEntity="PurchaseDetail", mappedBy="purchase_detail")
     */
    protected $purchase;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status;
    

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
     * @return Purchase
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
     * Set value
     *
     * @param string $value
     *
     * @return Purchase
     */
    public function setValue($value)
    {
        $this->value = $value;

        return $this;
    }

    /**
     * Get value
     *
     * @return string
     */
    public function getValue()
    {
        return $this->value;
    }
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->purchase = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * Add purchase
     *
     * @param \AppBundle\Entity\PurchaseDetail $purchase
     *
     * @return Purchase
     */
    public function addPurchase(\AppBundle\Entity\PurchaseDetail $purchase)
    {
        $this->purchase[] = $purchase;

        return $this;
    }

    /**
     * Remove purchase
     *
     * @param \AppBundle\Entity\PurchaseDetail $purchase
     */
    public function removePurchase(\AppBundle\Entity\PurchaseDetail $purchase)
    {
        $this->purchase->removeElement($purchase);
    }

    /**
     * Get purchase
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getPurchase()
    {
        return $this->purchase;
    }

    /**
     * Set status
     *
     * @param integer $status
     *
     * @return Purchase
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
}
