<?php

namespace App\Controller;

use App\Entity\GoOut;
use App\Entity\Participant;
use App\Entity\ParticipantGoOut;
use App\Entity\User;
use App\Form\GoOutType;
use App\Repository\GoOutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/go_out')]
class GoOutController extends AbstractController
{
    #[Route('/', name: 'app_go_out_index', methods: ['GET'])]
    public function index(GoOutRepository $goOutRepository): Response
    {
        return $this->render('go_out/index.html.twig', [
            'go_outs' => $goOutRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_go_out_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $goOut = new GoOut();
        $form = $this->createForm(GoOutType::class, $goOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($goOut);
            $entityManager->flush();

            return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('go_out/new.html.twig', [
            'go_out' => $goOut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_go_out_show', methods: ['GET'])]
    public function show(Request $request ,GoOut $goOut, GoOutRepository $goOutRepository): Response
    {
        $id = $request->attributes->get('id');
        $goOutParticipants = $goOutRepository->findBy(['id' => $id]);
        return $this->render('go_out/show.html.twig', [
            'go_out' => $goOut,
            'go_out_participants' => $goOutParticipants
        ]);
    }

    #[Route('/{id}/edit', name: 'app_go_out_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GoOut $goOut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoOutType::class, $goOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('go_out/edit.html.twig', [
            'go_out' => $goOut,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_go_out_delete', methods: ['POST'])]
    public function delete(Request $request, GoOut $goOut, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$goOut->getId(), $request->request->get('_token'))) {
            $entityManager->remove($goOut);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
    }

    // #[Route('/{id}', name: 'app_go_out_register', methods: ['POST'])]
    // public function register(Request $request, GoOut $goOut, EntityManagerInterface $entityManager): Response
    // {

    //     $goOut->getParticipantGoOuts($this->getUser());
    //     //je veux inscire mo participant avec sont id a un goout avec son id


    //     $entityManager->persist($goOut);
    //     $entityManager->flush();


    //     return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
    // }
}
