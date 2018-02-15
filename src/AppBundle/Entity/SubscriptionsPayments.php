<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="subscriptions_payments")
 */
class SubscriptionsPayments
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
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param mixed $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * @return mixed
     */
    public function getUserId()
    {
        return $this->user_id;
    }

    /**
     * @param mixed $user_id
     */
    public function setUserId($user_id)
    {
        $this->user_id = $user_id;
    }

    /**
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * @param mixed $value
     */
    public function setValue($value)
    {
        $this->value = $value;
    }

    /**
     * @return mixed
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * @param mixed $status
     */
    public function setStatus($status)
    {
        $this->status = $status;
    }

    /**
     * @return mixed
     */
    public function getTransactionIdPay()
    {
        return $this->transaction_id_pay;
    }

    /**
     * @param mixed $transaction_id_pay
     */
    public function setTransactionIdPay($transaction_id_pay)
    {
        $this->transaction_id_pay = $transaction_id_pay;
    }

    /**
     * @return mixed
     */
    public function getReferencePol()
    {
        return $this->reference_pol;
    }

    /**
     * @param mixed $reference_pol
     */
    public function setReferencePol($reference_pol)
    {
        $this->reference_pol = $reference_pol;
    }

    /**
     * @return mixed
     */
    public function getPayDate()
    {
        return $this->pay_date;
    }

    /**
     * @param mixed $pay_date
     */
    public function setPayDate($pay_date)
    {
        $this->pay_date = $pay_date;
    }

    /**
     * @return mixed
     */
    public function getUpdateDate()
    {
        return $this->update_date;
    }

    /**
     * @param mixed $update_date
     */
    public function setUpdateDate($update_date)
    {
        $this->update_date = $update_date;
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


}