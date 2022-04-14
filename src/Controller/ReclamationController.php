<?php

namespace App\Controller;

use App\Entity\Reclamation;
use App\Form\ReclamationType;
use App\Form\ReponseRecType;
use App\Repository\ReclamationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ReclamationController extends AbstractController
{
    /**
     * @Route("/reclamation", name="app_reclamation")
     */
    public function index(): Response
    {
        return $this->render('reclamation/index.html.twig', [
            'controller_name' => 'ReclamationController',
        ]);
    }

    /**
     * @param ReclamationRepository $repository
     * @return \Symfony\component\HttpFondation\Response
     * @Route ("/afficheC",name="afficheC")
     */
    public function afficherec(ReclamationRepository $repository){
       // $repos=$this->getDoctrine()->getRepository(Reclamation::class);
        $reclamation=$repository->findAll();
        return $this->render('Reclamation/afficherec.html.twig',['reclamation'=>$reclamation]);
    }

    /**

     * @route("/supp/{id}",name="d")
     */
    public function deleteReclamation($id)
    {
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $em = $this->getDoctrine()->getManager();
        $em->remove($reclamation);
        $em->flush();
        return $this->redirectToRoute("afficheC");
    }

    /**
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     * @Route ("Reclamation/add")
     */
    public function addReclamation(Request $request)
    {
        $reclamation = new Reclamation();
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($reclamation);
            $em->flush();
            $this->addFlash('info','Votre reclamation est ajoutée aves succée');
            return $this->redirectToRoute('afficheC');
        }
        return $this->render("Reclamation/add.html.twig",array('form'=>$form->createView()));
    }


    /**
     * @Route("/updateReclaamtion/{id}", name="update")
     */
    function Update(Request $request,$id){
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReclamationType::class, $reclamation);
        $form->add('modifier',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheC');
        }
        return $this->render("reclamation/Update.html.twig",array('form'=>$form->createView()));

    }
    /**
     * @Route("/repondreReclaamtion/{id}", name="reponse")
     */
    function Reponserec(Request $request,$id){
        $reclamation = $this->getDoctrine()->getRepository(Reclamation::class)->find($id);
        $form = $this->createForm(ReponseRecType::class, $reclamation);
        $form->add('repondre',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->flush();
            return $this->redirectToRoute('afficheC');
        }
        return $this->render("reclamation/reponse.html.twig",array('form'=>$form->createView()));

    }

    /**
     * @Route("/reclamation/rechercherec", name="recherche")
     */
         function rechercherec(ReclamationRepository $repository,Request $request){
            $data=$request->get('statuss');
            $reclamation=$repository->findBy(['status'=>$data]);
            return $this->render('Reclamation/afficherec.html.twig',['reclamation'=>$reclamation]);

    }
    /**
     * @Route("/reclamation/admin", name="displayadmin")
     */
    function idexadmin(){
        return $this->render('Admin/indexadmin.html.twig');

    }
}
