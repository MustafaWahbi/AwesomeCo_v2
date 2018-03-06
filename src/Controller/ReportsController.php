<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Validator\Constraints\Choice;
use Symfony\Component\Validator\Constraints\DateTime;

use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Entity\Reports;
use App\Entity\RequestedReports;

use Symfony\Component\HttpFoundation\Response;

class ReportsController extends Controller
{

    /**
     * @Route("/", name="report_list")
     */
    public function Reports(Request $request)
    {

        $general_reports = $this->getDoctrine()->getRepository('App:Reports')->findBy(['approved' => '1','type' => 'G']);
        $site_reports = $this->getDoctrine()->getRepository('App:Reports')->findBy(['approved' => '1','type' => 'S']);


        $report = new Reports();

        /* add new form */
        $form = $this->createFormBuilder($report)
            ->add('name',TextType::class,array('attr' => array('class' => 'form-control','style'=>'margin-bottom:15px')))
            ->add('description',TextareaType::class,array('attr' => array('class' => 'form-control','style'=>'margin-bottom:15px')))
            ->add('priority',ChoiceType::class,array('choices'=>array('low'=>'low','high'=>'high'),'attr' => array('class' => 'form-control','style'=>'margin-bottom:15px')))
            ->add('type',ChoiceType::class,array('choices'=>array('Select report type'=>'','General'=>'G','Site'=>'S'),'attr' => array('class' => 'form-control','style'=>'margin-bottom:15px')))
            ->add('cause',TextareaType::class,array('attr' => array('class' => 'form-control','style'=>'margin-bottom:15px;')))
            ->add('latitude',TextType::class,array('attr' => array('class' => 'form-control','style'=>'margin-bottom:15px;')))
            ->add('longitude',TextType::class,array('attr' => array('class' => 'form-control','style'=>'margin-bottom:15px;')))
            ->add('save',SubmitType::class,array('label'=>'Save','attr' => array('class' => 'btn btn-primary','style'=>'margin-bottom:15px')))->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $name=$form['name']->getData();
            $description=$form['description']->getData();
            $priority=$form['priority']->getData();
            $cause=$form['cause']->getData();
            $report_type=$form['type']->getData();
            $now = new \DateTime();

            //var_dump($report_type); die();
            $report->setName($name);
            $report->setType($report_type);
            $report->setDescription($description);
            $report->setPriority($priority);
            $report->setCause($cause);
            $report->setCreatedAt($now);

            $em = $this->getDoctrine()->getManager();
            $em ->persist($report);
            $em->flush();

            $this->addFlash('notice','create request has been sent to Manager');

            return $this->redirectToRoute('report_list');

        }

        /* edit form */
        $edit_form = $this->createFormBuilder($report)
            ->add('id',HiddenType::class,array('mapped' => false,'attr' => array('class' => 'edit_id')))
            ->add('parent_id',HiddenType::class,array('attr' => array('class' => 'edit_parent_id')))
            ->add('name',TextType::class,array('attr' => array('class' => 'edit_name form-control','style'=>'margin-bottom:15px')))
            ->add('description',TextareaType::class,array('attr' => array('class' => 'edit_description form-control','style'=>'margin-bottom:15px')))
            ->add('priority',ChoiceType::class,array('choices'=>array('low'=>'low','high'=>'high'),'attr' => array('class' => 'edit_priority form-control','style'=>'margin-bottom:15px')))
            ->add('type',ChoiceType::class,array('choices'=>array('Select report type'=>'','General'=>'G','Site'=>'S'),'attr' => array('class' => 'edit_type form-control','style'=>'margin-bottom:15px')))
            ->add('cause',TextareaType::class,array('attr' => array('class' => 'edit_cause form-control','style'=>'margin-bottom:15px;')))
            ->add('latitude',TextType::class,array('attr' => array('class' => 'edit_latitude form-control','style'=>'margin-bottom:15px;')))
            ->add('longitude',TextType::class,array('attr' => array('class' => 'edit_longitude form-control','style'=>'margin-bottom:15px;')))
            ->add('save',SubmitType::class,array('label'=>'Save','attr' => array('class' => 'btn btn-primary','style'=>'margin-bottom:15px')))->getForm();
        $edit_form->handleRequest($request);

        if($edit_form->isSubmitted() && $edit_form->isValid()){

            $now = new \DateTime();

            /*
             * update the parent report
             * */
            $report = $this->getDoctrine()->getRepository('App:Reports')->find($edit_form['id']->getData());
            $em = $this->getDoctrine()->getManager();
            $report->setUpdatedAt($now);

            $em->flush();///// update updated_date for the parent

            /*
             * Add new report as an update
             * */
            $name=$edit_form['name']->getData();
            $description=$edit_form['description']->getData();
            $priority=$edit_form['priority']->getData();
            $cause=$edit_form['cause']->getData();
            $latitude=$edit_form['latitude']->getData();
            $longitude=$edit_form['longitude']->getData();
            $now = new \DateTime();

            $new_report = new Reports();
            $new_report->setName($name);
            $new_report->setParentId($edit_form['id']->getData());
            $new_report->setType($edit_form['type']->getData());
            $new_report->setDescription($description);
            $new_report->setPriority($priority);
            $new_report->setLatitude($latitude);
            $new_report->setCause($cause);
            $new_report->setLongitude($longitude);
            $new_report->setCreatedAt($now);



            $em = $this->getDoctrine()->getManager();
            $em ->persist($new_report);
            $em->flush();



            $this->addFlash('notice','update request has been sent to Manager');

            return $this->redirectToRoute('report_list');

        }


        //, array('form' => $form->createView())
        return $this->render('reports/list_of_Reports.html.twig', array('general_reports' => $general_reports,'site_reports' => $site_reports,'form' => $form->createView(),'edit_form' => $edit_form->createView()));

    }

    /**
     * @Route("/approvalPage", name="approve_page")
     */
    public function approvalPage(Request $request)
    {



        $general_reports = $this->getDoctrine()->getRepository('App:Reports')->findBy(['approved' => '0','type' => 'G']);
        $site_reports = $this->getDoctrine()->getRepository('App:Reports')->findBy(['approved' => '0','type' => 'S']);
        $rejected_reports = $this->getDoctrine()->getRepository('App:Reports')->findBy(['approved' => '2']);

        $report = new Reports();
        $form = $this->createFormBuilder($report)
            ->add('Justification',TextareaType::class,array('attr' => array('class' => 'form-control','style'=>'margin-bottom:15px')))
            ->add('id',HiddenType::class,array( 'mapped' => false,'required' => false,'attr' => array(/*'style'=>'display:non'*/)))
            ->add('approved',HiddenType::class,array( 'required' => false,'attr' => array(/*'style'=>'display:non'*/)))
            ->add('save',SubmitType::class,array('label'=>'Save','attr' => array('class' => 'btn btn-primary','style'=>'margin-bottom:15px')))->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $em = $this->getDoctrine()->getManager();
            $report = $this->getDoctrine()->getRepository('App:Reports')->find($form['id']->getData());
            $report->setJustification($form['Justification']->getData());
            $report->setApproved($form['approved']->getData());

            if($report->getParentId() && $form['approved']->getData() == 1){

                $main_report = $this->getDoctrine()->getRepository('App:Reports')->find($report->getParentId());
                $main_report->setApproved(3);
            }

            $em->flush();


            if($form['approved']->getData() == 1)$this->addFlash('notice','Report approved successfully');
            if($form['approved']->getData() == 2)$this->addFlash('notice','Report rejected successfully');

            return $this->redirectToRoute('approve_page');
        }

        return $this->render('reports/approvalPage.html.twig', array('form' => $form->createView(),'general_reports' => $general_reports,'site_reports' => $site_reports,'rejected_reports' => $rejected_reports));
    }

    /**
     * @Route("/delete/{id}", name="delete_report")
     */
    public function deleteAction($id)
    {
        $report = $this->getDoctrine()->getRepository('App:Reports')->find($id);

        if($report){ // order found
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($report);
            $entityManager->flush();
            $this->addFlash('notice','Report successfully deleted !');
            return $this->redirectToRoute('report_list');
        }
        else {
            $this->addFlash("Report not found",'401');

            return $this->redirectToRoute('report_list');
        }
    }
}
