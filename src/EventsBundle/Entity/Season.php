<?php

namespace EventsBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Class Season
 * @package EventsBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="seasons")
 */
class Season
{
    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $seasonId;

    /**
     * @ORM\Column(type="string", length=100, nullable=false)
     */
    private $seasonName;

    /**
     * @ORM\Column(type="string", length=250, nullable=false)
     */
    private $seasonDescription;

    /**
     * Get seasonId
     *
     * @return integer
     */
    public function getSeasonId()
    {
        return $this->seasonId;
    }

    /**
     * Set seasonName
     *
     * @param string $seasonName
     *
     * @return Season
     */
    public function setSeasonName($seasonName)
    {
        $this->seasonName = $seasonName;

        return $this;
    }

    /**
     * Get seasonName
     *
     * @return string
     */
    public function getSeasonName()
    {
        return $this->seasonName;
    }

    /**
     * Set seasonDescription
     *
     * @param string $seasonDescription
     *
     * @return Season
     */
    public function setSeasonDescription($seasonDescription)
    {
        $this->seasonDescription = $seasonDescription;

        return $this;
    }

    /**
     * Get seasonDescription
     *
     * @return string
     */
    public function getSeasonDescription()
    {
        return $this->seasonDescription;
    }
}
