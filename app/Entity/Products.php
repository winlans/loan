<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Products
 *
 * @ORM\Table(name="products")
 * @ORM\Entity
 */
class Products
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255, nullable=false)
     */
    private $name;

    /**
     * @var string
     *
     * @ORM\Column(name="logo", type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @var integer
     *
     * @ORM\Column(name="min_loans", type="integer", nullable=false)
     */
    private $minLoans;

    /**
     * @var integer
     *
     * @ORM\Column(name="max_loans", type="integer", nullable=false)
     */
    private $maxLoans;

    /**
     * @var string
     *
     * @ORM\Column(name="rate", type="string", length=255, nullable=false)
     */
    private $rate;

    /**
     * @var integer
     *
     * @ORM\Column(name="fee", type="integer", nullable=false)
     */
    private $fee;

    /**
     * @var integer
     *
     * @ORM\Column(name="loan_time_min", type="integer", nullable=false)
     */
    private $loanTimeMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="loan_time_max", type="integer", nullable=false)
     */
    private $loanTimeMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="loan_period_min", type="integer", nullable=false)
     */
    private $loanPeriodMin;

    /**
     * @var integer
     *
     * @ORM\Column(name="loan_period_max", type="integer", nullable=false)
     */
    private $loanPeriodMax;

    /**
     * @var integer
     *
     * @ORM\Column(name="applications", type="bigint", nullable=false)
     */
    private $applications;

    /**
     * @var boolean
     *
     * @ORM\Column(name="sale", type="boolean", nullable=true)
     */
    private $sale;

    /**
     * @var boolean
     *
     * @ORM\Column(name="is_new", type="boolean", nullable=false)
     */
    private $isNew;

    /**
     * @var string
     *
     * @ORM\Column(name="apply_path", type="string", length=255, nullable=false)
     */
    private $applyPath;

    /**
     * @var string
     *
     * @ORM\Column(name="amount_limit_show", type="string", length=255, nullable=true)
     */
    private $amountLimitShow;

    /**
     * @var integer
     *
     * @ORM\Column(name="loan_time_show", type="integer", nullable=false)
     */
    private $loanTimeShow;

    /**
     * @var integer
     *
     * @ORM\Column(name="rate_show", type="integer", nullable=false)
     */
    private $rateShow;

    /**
     * @var string
     *
     * @ORM\Column(name="apply_cond", type="string", length=128, nullable=false)
     */
    private $applyCond;

    /**
     * @var string
     *
     * @ORM\Column(name="auerbach", type="string", length=255, nullable=false)
     */
    private $auerbach;

    /**
     * @var string
     *
     * @ORM\Column(name="descr", type="string", length=255, nullable=false)
     */
    private $descr;

    /**
     * @var string
     *
     * @ORM\Column(name="not_show", type="string", length=128, nullable=true)
     */
    private $notShow;

    /**
     * @var integer
     *
     * @ORM\Column(name="sort", type="integer", nullable=false)
     */
    private $sort;

    /**
     * @var boolean
     *
     * @ORM\Column(name="status", type="boolean", nullable=false)
     */
    private $status;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created", type="datetime", nullable=false)
     */
    private $created;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated", type="datetime", nullable=false)
     */
    private $updated;


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
     * @return Products
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
     * Set logo
     *
     * @param string $logo
     * @return Products
     */
    public function setLogo($logo)
    {
        $this->logo = $logo;

        return $this;
    }

    /**
     * Get logo
     *
     * @return string 
     */
    public function getLogo()
    {
        return $this->logo;
    }

    /**
     * Set minLoans
     *
     * @param integer $minLoans
     * @return Products
     */
    public function setMinLoans($minLoans)
    {
        $this->minLoans = $minLoans;

        return $this;
    }

    /**
     * Get minLoans
     *
     * @return integer 
     */
    public function getMinLoans()
    {
        return $this->minLoans;
    }

    /**
     * Set maxLoans
     *
     * @param integer $maxLoans
     * @return Products
     */
    public function setMaxLoans($maxLoans)
    {
        $this->maxLoans = $maxLoans;

        return $this;
    }

    /**
     * Get maxLoans
     *
     * @return integer 
     */
    public function getMaxLoans()
    {
        return $this->maxLoans;
    }

    /**
     * Set rate
     *
     * @param string $rate
     * @return Products
     */
    public function setRate($rate)
    {
        $this->rate = $rate;

        return $this;
    }

    /**
     * Get rate
     *
     * @return string 
     */
    public function getRate()
    {
        return $this->rate;
    }

    /**
     * Set fee
     *
     * @param integer $fee
     * @return Products
     */
    public function setFee($fee)
    {
        $this->fee = $fee;

        return $this;
    }

    /**
     * Get fee
     *
     * @return integer 
     */
    public function getFee()
    {
        return $this->fee;
    }

    /**
     * Set loanTimeMin
     *
     * @param integer $loanTimeMin
     * @return Products
     */
    public function setLoanTimeMin($loanTimeMin)
    {
        $this->loanTimeMin = $loanTimeMin;

        return $this;
    }

    /**
     * Get loanTimeMin
     *
     * @return integer 
     */
    public function getLoanTimeMin()
    {
        return $this->loanTimeMin;
    }

    /**
     * Set loanTimeMax
     *
     * @param integer $loanTimeMax
     * @return Products
     */
    public function setLoanTimeMax($loanTimeMax)
    {
        $this->loanTimeMax = $loanTimeMax;

        return $this;
    }

    /**
     * Get loanTimeMax
     *
     * @return integer 
     */
    public function getLoanTimeMax()
    {
        return $this->loanTimeMax;
    }

    /**
     * Set loanPeriodMin
     *
     * @param integer $loanPeriodMin
     * @return Products
     */
    public function setLoanPeriodMin($loanPeriodMin)
    {
        $this->loanPeriodMin = $loanPeriodMin;

        return $this;
    }

    /**
     * Get loanPeriodMin
     *
     * @return integer 
     */
    public function getLoanPeriodMin()
    {
        return $this->loanPeriodMin;
    }

    /**
     * Set loanPeriodMax
     *
     * @param integer $loanPeriodMax
     * @return Products
     */
    public function setLoanPeriodMax($loanPeriodMax)
    {
        $this->loanPeriodMax = $loanPeriodMax;

        return $this;
    }

    /**
     * Get loanPeriodMax
     *
     * @return integer 
     */
    public function getLoanPeriodMax()
    {
        return $this->loanPeriodMax;
    }

    /**
     * Set applications
     *
     * @param integer $applications
     * @return Products
     */
    public function setApplications($applications)
    {
        $this->applications = $applications;

        return $this;
    }

    /**
     * Get applications
     *
     * @return integer 
     */
    public function getApplications()
    {
        return $this->applications;
    }

    /**
     * Set sale
     *
     * @param boolean $sale
     * @return Products
     */
    public function setSale($sale)
    {
        $this->sale = $sale;

        return $this;
    }

    /**
     * Get sale
     *
     * @return boolean 
     */
    public function getSale()
    {
        return $this->sale;
    }

    /**
     * Set isNew
     *
     * @param boolean $isNew
     * @return Products
     */
    public function setIsNew($isNew)
    {
        $this->isNew = $isNew;

        return $this;
    }

    /**
     * Get isNew
     *
     * @return boolean 
     */
    public function getIsNew()
    {
        return $this->isNew;
    }

    /**
     * Set applyPath
     *
     * @param string $applyPath
     * @return Products
     */
    public function setApplyPath($applyPath)
    {
        $this->applyPath = $applyPath;

        return $this;
    }

    /**
     * Get applyPath
     *
     * @return string 
     */
    public function getApplyPath()
    {
        return $this->applyPath;
    }

    /**
     * Set amountLimitShow
     *
     * @param string $amountLimitShow
     * @return Products
     */
    public function setAmountLimitShow($amountLimitShow)
    {
        $this->amountLimitShow = $amountLimitShow;

        return $this;
    }

    /**
     * Get amountLimitShow
     *
     * @return string 
     */
    public function getAmountLimitShow()
    {
        return $this->amountLimitShow;
    }

    /**
     * Set loanTimeShow
     *
     * @param integer $loanTimeShow
     * @return Products
     */
    public function setLoanTimeShow($loanTimeShow)
    {
        $this->loanTimeShow = $loanTimeShow;

        return $this;
    }

    /**
     * Get loanTimeShow
     *
     * @return integer 
     */
    public function getLoanTimeShow()
    {
        return $this->loanTimeShow;
    }

    /**
     * Set rateShow
     *
     * @param integer $rateShow
     * @return Products
     */
    public function setRateShow($rateShow)
    {
        $this->rateShow = $rateShow;

        return $this;
    }

    /**
     * Get rateShow
     *
     * @return integer 
     */
    public function getRateShow()
    {
        return $this->rateShow;
    }

    /**
     * Set applyCond
     *
     * @param string $applyCond
     * @return Products
     */
    public function setApplyCond($applyCond)
    {
        $this->applyCond = $applyCond;

        return $this;
    }

    /**
     * Get applyCond
     *
     * @return string 
     */
    public function getApplyCond()
    {
        return $this->applyCond;
    }

    /**
     * Set auerbach
     *
     * @param string $auerbach
     * @return Products
     */
    public function setAuerbach($auerbach)
    {
        $this->auerbach = $auerbach;

        return $this;
    }

    /**
     * Get auerbach
     *
     * @return string 
     */
    public function getAuerbach()
    {
        return $this->auerbach;
    }

    /**
     * Set descr
     *
     * @param string $descr
     * @return Products
     */
    public function setDescr($descr)
    {
        $this->descr = $descr;

        return $this;
    }

    /**
     * Get descr
     *
     * @return string 
     */
    public function getDescr()
    {
        return $this->descr;
    }

    /**
     * Set notShow
     *
     * @param string $notShow
     * @return Products
     */
    public function setNotShow($notShow)
    {
        $this->notShow = $notShow;

        return $this;
    }

    /**
     * Get notShow
     *
     * @return string 
     */
    public function getNotShow()
    {
        return $this->notShow;
    }

    /**
     * Set sort
     *
     * @param integer $sort
     * @return Products
     */
    public function setSort($sort)
    {
        $this->sort = $sort;

        return $this;
    }

    /**
     * Get sort
     *
     * @return integer 
     */
    public function getSort()
    {
        return $this->sort;
    }

    /**
     * Set status
     *
     * @param boolean $status
     * @return Products
     */
    public function setStatus($status)
    {
        $this->status = $status;

        return $this;
    }

    /**
     * Get status
     *
     * @return boolean 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set created
     *
     * @param \DateTime $created
     * @return Products
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return \DateTime 
     */
    public function getCreated()
    {
        return $this->created;
    }

    /**
     * Set updated
     *
     * @param \DateTime $updated
     * @return Products
     */
    public function setUpdated($updated)
    {
        $this->updated = $updated;

        return $this;
    }

    /**
     * Get updated
     *
     * @return \DateTime 
     */
    public function getUpdated()
    {
        return $this->updated;
    }
}
