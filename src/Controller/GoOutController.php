<?php

namespace App\Controller;

use App\Entity\GoOut;
use App\Entity\Participant;
use App\Entity\ParticipantGoOut;
use App\Repository\SiteRepository;
use App\Form\GoOutCancel;
use App\Repository\ParticipantGoOutRepository;
use App\Form\GoOutType;
use App\Repository\GoOutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Status;

#[Route('/user/go_out')]
class GoOutController extends AbstractController
{
    #[Route('/', name: 'app_go_out_index', methods: ['GET'])]
    public function index(GoOutRepository $goOutRepository, SiteRepository $siteRepository, SessionInterface $session ): Response
    {
        $searchParams = $session->get('search_params', []);

        if (!empty($searchParams)) {
            $go_outs = $goOutRepository->findBySearchParams($searchParams);
        } else {
            $go_outs = $goOutRepository->findAll();
        }

        $sites = $siteRepository->findAll();

        return $this->render('go_out/index.html.twig', [
            'go_outs' => $go_outs,
            'sites' => $sites,
        ]);
    }

    #[Route('/organize/new', name: 'app_go_out_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {

        $goOut = new GoOut();

        $userId = $this->getUser()->getId();
        
        $form = $this->createForm(GoOutType::class, $goOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $goOut->setParticipant($entityManager->getRepository(Participant::class)->find($userId));
            $entityManager->persist($goOut);
            $entityManager->flush();

            return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('go_out/new.html.twig', [
            'go_out' => $goOut,
            'form' => $form,
        ]);
    }

    #[Route('/search', name: 'app_go_out_search', methods: ['GET'])]
    public function search(Request $request, SessionInterface $session): Response
    {
        $userID = $this->getUser()->getId();
        $search = $request->query->get('search');
        $siteID = $request->query->get('site');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $organizing = $request->query->get('filter1');
        $registered = $request->query->get('filter2');
        $notRegistered = $request->query->get('filter3');
        $completed = $request->query->get('filter4');

        $searchParams = [
            'userID' => $userID,
            'search' => $search,
            'site' => $siteID,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'organizing' => $organizing,
            'registered' => $registered,
            'notRegistered' => $notRegistered,
            'completed' => $completed,
        ];
        $session->set('search_params', $searchParams);

        return $this->redirectToRoute('app_go_out_index');
    }

    #[Route('/{id}', name: 'app_go_out_show', methods: ['GET'])]
    public function show(Request $request ,GoOut $goOut, ParticipantGoOutRepository $participantGoOutRepository): Response
    {
        $id = $request->attributes->get('id');
        $goOutParticipants = $participantGoOutRepository->findBy(['goOut' => $id]);
        return $this->render('go_out/show.html.twig', [
            'go_out' => $goOut,
            'go_out_participants' => $goOutParticipants
        ]);
    }

    #[Route('/profile/participant/{id}', name: 'app_go_out_show_participant', methods: ['GET'])]
    public function showParticipant(Participant $participant): Response
    {
        return $this->render('go_out/show_participant.html.twig', [
            'participant' => $participant,
        ]);
    }

    #[Route('/organize/{id}/edit', name: 'app_go_out_edit', methods: ['GET', 'POST'])]
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

    #[Route('/{id}/cancel', name: 'app_go_out_cancel', methods: ['GET', 'POST'])]
    public function cancel(Request $request, GoOut $goOut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(GoOutCancel::class, $goOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            //$goOut->setStatus(); statut is intger 

            $goOut->setStatus($entityManager->getRepository(Status::class)->find(5));

            $entityManager->flush();

            return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('go_out/cancel.html.twig', [
            'go_out' => $goOut,
            'form_cancel' => $form,
        ]);
    }
    
}
