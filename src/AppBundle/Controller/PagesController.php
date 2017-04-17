<?php

namespace AppBundle\Controller;


use AppBundle\Entity\StaffMember;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\Season;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;

/**
 * Class PagesController
 * @package AppBundle\Controller
 * Controller class to handle all pages
 * within the system
 */
class PagesController extends Controller
{

    /**
     * Add a new staff member
     * @Route("/add-new-staff")
     */
    public function addNewStaffMember(Request $request)
    {
        $staffMember = new StaffMember();
        $staffMember->setName("Set name here");
        $staffMember->setJobTitle("Set their job title here");

        $form = $this->createFormBuilder($staffMember)
            ->add('name', TextType::class)
            ->add('jobTitle', TextType::class)
            ->add('save', SubmitType::class, array('label' => 'Create New Staff Member'))
            ->getForm();

        $form->handleRequest($request);

        if (($form->isSubmitted())
            && ($form->isValid())) {
            $staffMember = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($staffMember);
            $em->flush();

            return $this->displayExistingStaffMembers();
        }

        return $this->render('forms/newstaff.html.twig', array(
            'form' => $form->createView(),
        ));

    }

    /**
     * Display all the current staff members
     * @Route("/staff")
     */
    public function displayExistingStaffMembers()
    {
        $em = $this->getDoctrine()->getManager();
        $staffMembers  = $em->getRepository("AppBundle:StaffMember")->findAll();

        $data = array();
        $data['staffmembers'] = $staffMembers;

        return $this->render('campaignsapp/showstaff.html.twig', $data);

    }


    /**
     * Show all campaigns
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/campaigns")
     */
    public function displayExistingCampaigns()
    {
        $em = $this->getDoctrine()->getManager();
        $allCampaigns = $em->getRepository("AppBundle:Campaign")->findAll();

        $campaigns = array();
        foreach($allCampaigns as $soleCampaign)
        {
            $staffMemberObj = $em->getRepository("AppBundle:StaffMember")->findBy(array('id' => $soleCampaign->getStaffMemberId()));
            $soleCampaign->setStaffMemberName($staffMemberObj[0]->getName());
            $campaigns[] = $soleCampaign;
        }

        $data = array();
        $data['campaigns'] = $campaigns;

        return $this->render('campaignsapp/showcampaigns.html.twig', $data);
    }




    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add-campaign")
     */
    public function addNewCampaign(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $staffMembers  = $em->getRepository("AppBundle:StaffMember")->findAll();

        foreach($staffMembers as $staffMember)
        {
            $staffMembersForDropDown[$staffMember->getName()] = $staffMember->getID();
        }


        $campaign = new Campaign();
        $campaign->setCampaignName("Campaign Name");
        $campaign->setCampaignDescription("Description");
        $campaign->setCampaignStartDate(date("Y-m-d H:i:s"));
        $campaign->setCampaignEndDate(date("Y-m-d H:i:s"));


        $form = $this->createFormBuilder($campaign)
            ->add('campaignname', TextType::class, array('label' => 'Name'))
            ->add('campaigndescription', TextType::class, array('label' => 'Brief Description'))
            ->add('campaignstartdate', TextType::class, array('label' => 'Start Date'))
            ->add('campaignenddate', TextType::class, array('label' => 'End Date'))
            ->add('staffmemberid', ChoiceType::class, array(
                'choices' => $staffMembersForDropDown,
                'label' => 'Staff owner'
                ))



            ->add('save', SubmitType::class, array('label' => 'Create New Campaign'))
            ->getForm();

        $form->handleRequest($request);

        if (($form->isSubmitted())
            && ($form->isValid())) {
            $campaign = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($campaign);
            $em->flush();

            return $this->displayExistingCampaigns();
        }

        return $this->render('forms/newcampaign.html.twig', array(
            'form' => $form->createView(),
        ));
    }



}