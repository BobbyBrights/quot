<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="suscritors")
 */
class Suscritors
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="User", inversedBy="suscritor")
     * @ORM\JoinColumn(name="user_suscritor", referencedColumnName="id", nullable=TRUE)
     **/
    protected $user_suscritor;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $status;

    /** @ORM\Column(name="pay_date", type="integer", length=255, nullable=true) */
    protected $pay_date;

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
    public function getUserSuscritor()
    {
        return $this->user_suscritor;
    }

    /**
     * @param mixed $user_suscritor
     */
    public function setUserSuscritor($user_suscritor)
    {
        $this->user_suscritor = $user_suscritor;
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


}