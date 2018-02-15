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
    
    /** @ORM\Column(name="shirt_id", type="integer", length=255, nullable=true) */
    protected $shirt_id;
    
    /**
     * @ORM\OneToMany(targetEntity="PurchaseDetail", mappedBy="purchase_detail")
     */
    protected $purchase;
    
    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status;
    
    /** @ORM\Column(name="transaction_id_pay", type="string", length=255, nullable=true) */
    protected $transaction_id_pay;
    
    /** @ORM\Column(name="reference_pol", type="string", length=255, nullable=true) */
    protected $reference_pol;

    /** @ORM\Column(name="pay_date", type="integer", length=255, nullable=true) */
    protected $pay_date;

    /** @ORM\Column(name="update_date", type="integer", length=255, nullable=true) */
    protected $update_date;

    /** @ORM\Column(name="confirmed", type="integer", length=255, nullable=true) */
    protected $confirmed;

    /** @ORM\Column(name="reference", type="string", length=255, nullable=true) */
    protected $reference;

    /**
     * @return mixed
     */
    public function getReference()
    {
        return $this->reference;
    }

    /**
     * @param mixed $reference
     */
    public function setReference($reference)
    {
        $this->reference = $reference;
    }

    /**
     * @return mixed
     */
    public function getConfirmed()
    {
        return $this->confirmed;
    }

    /**
     * @param mixed $confirmed
     */
    public function setConfirmed($confirmed)
    {
        $this->confirmed = $confirmed;
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

    /**
     * Set shirtId
     *
     * @param integer $shirtId
     *
     * @return Purchase
     */
    public function setShirtId($shirtId)
    {
        $this->shirt_id = $shirtId;

        return $this;
    }

    /**
     * Get shirtId
     *
     * @return integer
     */
    public function getShirtId()
    {
        return $this->shirt_id;
    }

    /**
     * Set transactionIdPay
     *
     * @param integer $transactionIdPay
     *
     * @return Purchase
     */
    public function setTransactionIdPay($transactionIdPay)
    {
        $this->transaction_id_pay = $transactionIdPay;

        return $this;
    }

    /**
     * Get transactionIdPay
     *
     * @return integer
     */
    public function getTransactionIdPay()
    {
        return $this->transaction_id_pay;
    }

    /**
     * Set referencePol
     *
     * @param integer $referencePol
     *
     * @return Purchase
     */
    public function setReferencePol($referencePol)
    {
        $this->reference_pol = $referencePol;

        return $this;
    }

    /**
     * Get referencePol
     *
     * @return integer
     */
    public function getReferencePol()
    {
        return $this->reference_pol;
    }

    /**
     * Set payDate
     *
     * @param integer $payDate
     *
     * @return Purchase
     */
    public function setPayDate($payDate)
    {
        $this->pay_date = $payDate;

        return $this;
    }

    /**
     * Get payDate
     *
     * @return integer
     */
    public function getPayDate()
    {
        return $this->pay_date;
    }

    /**
     * Set updateDate
     *
     * @param integer $updateDate
     *
     * @return Purchase
     */
    public function setUpdateDate($updateDate)
    {
        $this->update_date = $updateDate;

        return $this;
    }

    /**
     * Get updateDate
     *
     * @return integer
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }
}
