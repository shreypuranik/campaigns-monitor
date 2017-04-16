<?php

namespace AppBundle\Controller;

use AppBundle\Entity\StaffMember;
use AppBundle\Entity\Campaign;
use AppBundle\Entity\Season;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

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
}