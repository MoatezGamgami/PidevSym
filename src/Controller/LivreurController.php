<?php

namespace App\Controller;

use App\Entity\Livreur;
use App\Form\LivreurType;
use App\Repository\LivreurRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use AppBundle\Entity;
;
use Doctrine\Bundle\FixturesBundle;
use Doctrine\Common\Persistence;

/**
 * @Route("/livreur")
 */
class LivreurController extends AbstractController
{
    /**
     * @Route("/", name="app_livreur_index", methods={"GET"})
     */
    public function index(LivreurRepository $livreurRepository): Response
    {
        return $this->render('livreur/index.html.twig', [
            'livreurs' => $livreurRepository->findAll(),
        ]);
    }

    /**
     * @Route("new", name="app_livreur_new", methods={"GET", "POST"})
     */
    public function new(Request $request, LivreurRepository $livreurRepository): Response
    {
        $livreur = new Livreur();
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreurRepository->add($livreur);
            $sid = "AC563bff714d65d46ca7a2c7684dc35264"; // Your Account SID from www.twilio.com/console
            $token = "9f9e08a6395701adf1d3cd434942739f"; // Your Auth Token from www.twilio.com/console

            $client = new Twilio\Rest\Client($sid, $token);
            $message = $client->messages->create(
                '+21620313998', // Text this number
                [
                    'from' => '+18507861860', // From a valid Twilio number
                    'body' => 'Hello from Twilio!'
                ]
            );
            return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/new.html.twig', [
            'livreur' => $livreur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_livreur_show", methods={"GET"})
     */
    public function show(Livreur $livreur): Response
    {
        return $this->render('livreur/show.html.twig', [
            'livreur' => $livreur,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="app_livreur_edit", methods={"GET", "POST"})
     */
    public function edit(Request $request, Livreur $livreur, LivreurRepository $livreurRepository): Response
    {
        $form = $this->createForm(LivreurType::class, $livreur);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $livreurRepository->add($livreur);
            return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('livreur/edit.html.twig', [
            'livreur' => $livreur,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="app_livreur_delete", methods={"POST"})
     */
    public function delete(Request $request, Livreur $livreur, LivreurRepository $livreurRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$livreur->getId(), $request->request->get('_token'))) {
            $livreurRepository->remove($livreur);
        }

        return $this->redirectToRoute('app_livreur_index', [], Response::HTTP_SEE_OTHER);
    }
}
