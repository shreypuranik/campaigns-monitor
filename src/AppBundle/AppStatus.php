<?php

namespace AppBundle;

use Doctrine\ORM\EntityManager;

/**
 * Class AppStatus
 *
 * Class to return status information
 * by checking entries in the database
 */
class AppStatus
{
    private $em;

    /**
     * Set up the local EM variable
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $staffMembers = $this->em->getRepository("AppBundle:StaffMember")->findAll();
        $campaigns = $this->em->getRepository("AppBundle:Campaign")->findAll();
        $seasons = $this->em->getRepository("AppBundle:Season")->findAll();

        $this->baseData = array();
        $this->baseData['staff'] = count($staffMembers);
        $this->baseData['campaigns'] = count($campaigns);
        $this->baseData['seasons'] = count($seasons);
        $this->baseData['newbuild'] = ((array_sum($this->baseData)) == 0 ? true: false);

    }

    /**
     * Get the existing base data
     * (all the various entities)
     * @return array
     */
    public function getExistingData()
    {
        return $this->baseData;
    }

    /**
     * Return a boolean response
     * @return bool
     */
    public function isNewBuild()
    {
        return $this->baseData['newbuild'];
    }



}