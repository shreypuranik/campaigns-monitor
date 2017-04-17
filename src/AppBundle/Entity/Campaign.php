<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Event
 * @package AppBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="campaigns")
 */
class Campaign
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $campaignId;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $campaignName;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $campaignStartDate;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    private $campaignEndDate;

    /**
     * @ORM\Column(type="string", length=200)
     */
    private $campaignDescription;

    /**
     * @ORM\Column(type="integer")
     */
    private $staffMemberId;

    private $staffMemberName;


    /**
     * Get campaignId
     *
     * @return integer
     */
    public function getCampaignId()
    {
        return $this->campaignId;
    }

    /**
     * Set campaignName
     *
     * @param string $campaignName
     *
     * @return Campaign
     */
    public function setCampaignName($campaignName)
    {
        $this->campaignName = $campaignName;

        return $this;
    }

    /**
     * Get campaignName
     *
     * @return string
     */
    public function getCampaignName()
    {
        return $this->campaignName;
    }

    /**
     * Set campaignStartDate
     *
     * @param \DateTime $campaignStartDate
     *
     * @return Campaign
     */
    public function setCampaignStartDate($campaignStartDate)
    {
        $this->campaignStartDate = $campaignStartDate;

        return $this;
    }

    /**
     * Get campaignStartDate
     *
     * @return \DateTime
     */
    public function getCampaignStartDate()
    {
        return $this->campaignStartDate;
    }

    /**
     * Set campaignEndDate
     *
     * @param \DateTime $campaignEndDate
     *
     * @return Campaign
     */
    public function setCampaignEndDate($campaignEndDate)
    {
        $this->campaignEndDate = $campaignEndDate;

        return $this;
    }

    /**
     * Get campaignEndDate
     *
     * @return \DateTime
     */
    public function getCampaignEndDate()
    {
        return $this->campaignEndDate;
    }

    /**
     * Set campaignDescription
     *
     * @param string $campaignDescription
     *
     * @return Campaign
     */
    public function setCampaignDescription($campaignDescription)
    {
        $this->campaignDescription = $campaignDescription;

        return $this;
    }

    /**
     * Get campaignDescription
     *
     * @return string
     */
    public function getCampaignDescription()
    {
        return $this->campaignDescription;
    }

    /**
     * Set staffMemberId
     *
     * @param integer $staffMemberId
     *
     * @return Campaign
     */
    public function setStaffMemberId($staffMemberId)
    {
        $this->staffMemberId = $staffMemberId;

        return $this;
    }

    /**
     * Get staffMemberId
     *
     * @return integer
     */
    public function getStaffMemberId()
    {
        return $this->staffMemberId;
    }

    /**
     * Set staffMemberName
     * @param $staffMemberName
     */
    public function setStaffMemberName($staffMemberName)
    {
        $this->staffMemberName = $staffMemberName;
    }

    /**
     * Get staffMemberName
     * @return mixed
     */
    public function getStaffMemberName()
    {
        return $this->staffMemberName;
    }

}
