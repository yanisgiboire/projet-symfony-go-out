<?php

namespace App\Controller;

use App\Entity\GoOut;
use App\Entity\Participant;
use App\Entity\User;
use App\Repository\ParticipantRepository;
use App\Repository\SiteRepository;
use App\Form\GoOutCancel;
use App\Repository\ParticipantGoOutRepository;
use App\Form\GoOutType;
use App\Repository\GoOutRepository;
use App\Repository\StatusRepository;
use App\Service\CheckGoOutStatusService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Attribute\Route;
use App\Entity\Status;

#[Route('/goout')]
class GoOutController extends BaseController
{
    #[Route('/', name: 'app_go_out_index', methods: ['GET'])]
    public function index(SessionInterface $session, GoOutRepository $goOutRepository, SiteRepository $siteRepository, participantGoOutRepository $participantGoOutRepository, CheckGoOutStatusService $checkGoOutStatusService): Response
    {
        $userID = $this->getUser()->getId();
        $searchParams = $session->get('search_params', []);
        $go_outs = $goOutRepository->findForIndex();
        $sites = $siteRepository->findAll();
        $allParticipant = $participantGoOutRepository->findAll();

        return $this->render('go_out/index.html.twig', [
            'go_outs' => $go_outs,
            'sites' => $sites,
            'participantGoOut' => $allParticipant,
            'searchParams' => $searchParams,
            'status' => self::STATUS
        ]);
    }

    #[Route('/new', name: 'app_go_out_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ParticipantRepository $participantRepository): Response
    {
        $goOut = new GoOut();

        /** @var User $user */
        $user = $this->getUser();

        $form = $this->createForm(GoOutType::class, $goOut);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $goOut->setOrganizer($participantRepository->find($user->getParticipant()));
            $goOut->setStatus($entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_CREATED]));

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
    public function search(Request $request, SessionInterface $session, GoOutRepository $goOutRepository, SiteRepository $siteRepository, participantGoOutRepository $participantGoOutRepository): Response
    {
        $userID = $this->getUser()->getId();
        $search = $request->query->get('search');
        $siteID = $request->query->get('site');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $organizing = $request->query->get('organizing');
        $registered = $request->query->get('registered');
        $notRegistered = $request->query->get('notRegistered');
        $completed = $request->query->get('completed');
        $reset = $request->query->get('reset');

        if ($reset === true || $reset === 'true' || $reset === 'on' || $reset === 1 || $reset === '1') {
            $search = null;
            $siteID = null;
            $startDate = null;
            $endDate = null;
            $organizing = null;
            $registered = null;
            $notRegistered = null;
            $completed = null;
        }

        if ($registered === 'on' && $notRegistered === 'on') {
            $registered = 'off';
            $notRegistered = 'off';
        }

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

        if (!empty($search) || !empty($siteID) || !empty($startDate) || !empty($endDate) || !empty($organizing) || !empty($registered) || !empty($notRegistered) || !empty($completed)) {
            $go_outs = $goOutRepository->findBySearchParams($searchParams);
        } else {
            $go_outs = $goOutRepository->findForIndex($userID);
        }

        $sites = $siteRepository->findAll();

        return $this->render('go_out/index.html.twig', [
            'go_outs' => $go_outs,
            'sites' => $sites,
            'searchParams' => $searchParams,
            'status' => self::STATUS
        ]);
    }

    // #[Route('/cancelSearch', name: 'app_go_out_cancelSearch', methods: ['GET', 'POST'])]
    // public function cancelSearch(SessionInterface $session): Response
    // {
    //     $session->remove('search_params');

    //     return $this->redirectToRoute('app_go_out_index');
    // }

    #[Route('/{id}', name: 'app_go_out_show', methods: ['GET'])]
    public function show(Request $request, GoOut $goOut, ParticipantGoOutRepository $participantGoOutRepository): Response
    {
        $id = $request->attributes->get('id');
        $goOutParticipants = $participantGoOutRepository->findBy(['goOut' => $id]);
        return $this->render('go_out/show.html.twig', [
            'go_out' => $goOut,
            'go_out_participants' => $goOutParticipants,
            'status' => self::STATUS
        ]);
    }

    #[Route('/{id}/publish', name: 'app_go_out_publish', methods: ['GET', 'POST'])]
    public function publish(GoOut $goOut, StatusRepository $statusRepository,  EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        if ($currentUser !== $goOut->getOrganizer()->getUser()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à accéder à cette page.");
        }

        $goOut->setStatus($statusRepository->findOneBy(['libelle' => Status::STATUS_OPENED ]));
        $entityManager->flush();

        return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
    }

    #[Route('/profile/participant/{id}', name: 'app_go_out_show_participant', methods: ['GET'])]
    public function showParticipant(Participant $participant): Response
    {
        return $this->render('go_out/show_participant.html.twig', [
            'participant' => $participant,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_go_out_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, GoOut $goOut, EntityManagerInterface $entityManager): Response
    {
        $currentUser = $this->getUser();

        if ($currentUser !== $goOut->getOrganizer()->getUser()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à accéder à cette page.");
        }

        if ($goOut->getStatus()->getLibelle() !== Status::STATUS_CREATED) {
            $this->addFlash('error', 'Vous ne pouvez pas modifier une sortie dont le statut n\'est pas "Créé".');
            return $this->redirectToRoute('app_go_out_index');
        }

        $form = $this->createForm(GoOutType::class, $goOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'La sortie ' . $goOut->getName() . ' a bien été edité.');

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
        $currentUser = $this->getUser();

        if ($currentUser !== $goOut->getOrganizer()->getUser()) {
            throw new AccessDeniedException("Vous n'êtes pas autorisé à accéder à cette page.");
        }

        $form = $this->createForm(GoOutCancel::class, $goOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $goOut->setStatus($entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_CANCELED]));
            $entityManager->flush();

            $this->addFlash('success', 'La sortie ' . $goOut->getName() . ' a bien été annulée.');
            return $this->redirectToRoute('app_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('go_out/cancel.html.twig', [
            'go_out' => $goOut,
            'form_cancel' => $form,
        ]);
    }
}
