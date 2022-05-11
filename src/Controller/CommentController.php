<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Reclamation;
use App\Form\CommentFromType;
use App\Form\ReclamationType;
use App\Repository\CommentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Routing\Annotation\Route;
class CommentController extends AbstractController
{
    /**
     * @Route("/comment", name="app_comment")
     */
    public function index(): Response
    {
        return $this->render('comment/index.html.twig', [
            'controller_name' => 'CommentController',
        ]);
    }
    /**
     * @param CommentRepositoryRepository $repository
     * @return \Symfony\component\HttpFondation\Response
     * @Route ("/afficheCommentaire",name="afficheCommentaire")
     */
    public function afficherec(CommentRepository $repository){
        // $repos=$this->getDoctrine()->getRepository(Reclamation::class);
        $Comment=$repository->findAll();
        return $this->render('Comment/affichecommentaire.html.twig',['Comment'=>$Comment]);
    }
    /**

     * @route("/supp/{id}",name="b")
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
     * @Route ("Comment/add")
     */
    public function addCommentaire(Request $request)
    {
        $commentaire = new Comment();
        $form = $this->createForm(CommentFromType::class, $commentaire);
        $form->add('Ajouter',SubmitType::class);
        $form->handleRequest($request);
        if ($form->isSubmitted()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($commentaire);
            $em->flush();
            return $this->redirectToRoute('afficheCommentaire');
        }
        return $this->render("Comment/addcomment.html.twig",array('form'=>$form->createView()));
    }
    /**
     * @Route("/updateReclaamtion/{id}", name="updatee")
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

}
