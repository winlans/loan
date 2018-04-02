<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * AccessRecord
 *
 * @ORM\Table(name="access_record")
 * @ORM\Entity
 */
class AccessRecord extends BaseEntity
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="bigint", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="product_id", type="integer", nullable=false)
     */
    private $productId;

    /**
     * @var string
     *
     * @ORM\Column(name="product_name", type="string", length=64, nullable=true)
     */
    private $productName;

    /**
     * @var integer
     *
     * @ORM\Column(name="user_id", type="integer", nullable=false)
     */
    private $userId;

    /**
     * @var integer
     *
     * @ORM\Column(name="d_count", type="integer", nullable=false)
     */
    private $dCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="a_count", type="integer", nullable=false)
     */
    private $aCount;

    /**
     * @var integer
     *
     * @ORM\Column(name="created", type="integer", nullable=false)
     */
    private $created;


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
     * Set productId
     *
     * @param integer $productId
     * @return AccessRecord
     */
    public function setProductId($productId)
    {
        $this->productId = $productId;

        return $this;
    }

    /**
     * Get productId
     *
     * @return integer 
     */
    public function getProductId()
    {
        return $this->productId;
    }

    /**
     * Set productName
     *
     * @param string $productName
     * @return AccessRecord
     */
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get productName
     *
     * @return string 
     */
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return AccessRecord
     */
    public function setUserId($userId)
    {
        $this->userId = $userId;

        return $this;
    }

    /**
     * Get userId
     *
     * @return integer 
     */
    public function getUserId()
    {
        return $this->userId;
    }

    /**
     * Set dCount
     *
     * @param integer $dCount
     * @return AccessRecord
     */
    public function setDCount($dCount)
    {
        $this->dCount = $dCount;

        return $this;
    }

    /**
     * Get dCount
     *
     * @return integer 
     */
    public function getDCount()
    {
        return $this->dCount;
    }

    /**
     * Set aCount
     *
     * @param integer $aCount
     * @return AccessRecord
     */
    public function setACount($aCount)
    {
        $this->aCount = $aCount;

        return $this;
    }

    /**
     * Get aCount
     *
     * @return integer 
     */
    public function getACount()
    {
        return $this->aCount;
    }

    /**
     * Set created
     *
     * @param integer $created
     * @return AccessRecord
     */
    public function setCreated($created)
    {
        $this->created = $created;

        return $this;
    }

    /**
     * Get created
     *
     * @return integer 
     */
    public function getCreated()
    {
        return $this->created;
    }
}
