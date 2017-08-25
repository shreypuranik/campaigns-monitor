<?php

namespace EventsBundle\Controller;


use EventsBundle\Entity\StaffMember;
use EventsBundle\Entity\Campaign;
use EventsBundle\Entity\Season;
use EventsBundle\AppStatus;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\Query\Expr\Select;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\DomCrawler\Field\TextareaFormField;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


/**
 * Class PagesController
 * @package EventsBundle\Controller
 * Controller class to handle all pages
 * within the system
 */
class PagesController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/")
     */
    public function indexAction()
    {
        $baseData = $this->get('app.app_status')->getExistingData();

        return $this->render('campaignsapp/homepage.html.twig', $baseData);
    }

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
        $staffMembers  = $em->getRepository("EventsBundle:StaffMember")->findAll();


        $data = array();
        $data['staffmembers'] = $staffMembers;
        $data['totalcount'] = count($staffMembers);
        $data['newbuild'] = $this->get('app.app_status')->isNewBuild();


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
        $allCampaigns = $em->getRepository("EventsBundle:Campaign")->findAll();

        $campaigns = array();
        foreach($allCampaigns as $soleCampaign)
        {
            $staffMemberObj = $em->getRepository("EventsBundle:StaffMember")->findBy(array('id' => $soleCampaign->getStaffMemberId()));
            $soleCampaign->setStaffMemberName($staffMemberObj[0]->getName());

            $seasonObj = $em->getRepository("EventsBundle:Season")->findOneBy(array('seasonId' => $soleCampaign->getSeasonId()));
            $soleCampaign->setSeasonName($seasonObj->getSeasonName());
            $campaigns[] = $soleCampaign;
        }

        $data = array();

        $data['campaigns'] = $campaigns;
        $data['totalcampaigns'] = count($campaigns);
        $data['newbuild'] = $this->get('app.app_status')->isNewBuild();

        return $this->render('campaignsapp/showcampaigns.html.twig', $data);
    }


    /**
     * Show all the existing season/theme records
     * @Route("/seasons")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function displayExistingSeasons()
    {
        $em = $this->getDoctrine()->getManager();
        $seasons = $em->getRepository("EventsBundle:Season")->findAll();
        foreach($seasons as $season){
            $campaignsForSeason = $em->getRepository("EventsBundle:Campaign")->findBySeasonId($season->getSeasonId());
            $season->campaigns = count($campaignsForSeason);
        }

        $data = array();
        $data['seasons'] = $seasons;
        $data['totalcount'] = count($seasons);
        $data['newbuild'] = $this->get('app.app_status')->isNewBuild();

        return $this->render('campaignsapp/showseasons.html.twig', $data);

    }

    /**
     * Add a new campaign season
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add-season")
     */
    public function addNewSeason(Request $request)
    {
        $season = new Season();
        $season->setSeasonName("Season name goes here ");
        $season->setSeasonDescription("Longer description goes here");

        $form = $this->createFormBuilder($season)
            ->add('seasonname', TextType:: class, array('label' => 'Name (eg Christmas)'))
            ->add('seasondescription', TextareaType::class, array('label' => 'Description'))
            ->add('save', SubmitType::class, array('label' => 'Create new season'))
            ->getForm();

        $form->handleRequest($request);

        if (($form->isSubmitted())
            && ($form->isValid())) {
            $season = $form->getData();
            $em = $this->getDoctrine()->getManager();
            $em->persist($season);
            $em->flush();

            return $this->displayExistingSeasons();
        }

        return $this->render('forms/newseason.html.twig', array(
            'form' => $form->createView(),
        ));

    }








    /**
     * Add a new campaign
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route("/add-campaign")
     */
    public function addNewCampaign(Request $request)
    {

        $em = $this->getDoctrine()->getManager();
        $staffMembers  = $em->getRepository("EventsBundle:StaffMember")->findAll();

        foreach($staffMembers as $staffMember)
        {
            $staffMembersForDropDown[$staffMember->getName()] = $staffMember->getID();
        }

        $allSeasons = $em->getRepository("EventsBundle:Season")->findAll();

        foreach($allSeasons as $season)
        {
            $seasons[$season->getSeasonName()] = $season->getSeasonID();
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
            ->add('seasonid', ChoiceType::class, array(
                'choices' => $seasons,
                'label' => 'Campaign Season/Theme'
            ))
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