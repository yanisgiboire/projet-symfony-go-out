<?php

namespace App\Controller;

use App\Entity\GoOut;
use App\Entity\ParticipantGoOut;
use App\Form\ParticipantGoOutType;
use App\Repository\ParticipantGoOutRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ParticipantRepository;
use PhpParser\Builder\Param;
use PhpParser\Node\Scalar\MagicConst\Dir;

#[Route('/participant/go_out')]
class ParticipantGoOutController extends AbstractController
{
    #[Route('/', name: 'app_participant_go_out_index', methods: ['GET'])]
    public function index(ParticipantGoOutRepository $participantGoOutRepository): Response
    {
        return $this->render('participant_go_out/index.html.twig', [
            'participant_go_outs' => $participantGoOutRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'app_participant_go_out_new', methods: ['GET', 'POST'])]
    
    public function new(EntityManagerInterface $entityManager, GoOut $goOut, ParticipantRepository $participantRepository): Response
    {
        // Check if the user is authenticated
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login'); // Redirect to the login page
        }

        // Retrieve the user ID
        /** @var User $user */
        $user = $this->getUser();
        $participant = $user->getParticipant();

        if ($participant) {
            if (($goOut->getMaxNbInscriptions() > count($goOut->getParticipantGoOuts()))) {
                if ($goOut->getLimitDateInscription()->format('Y-m-d') > (new \DateTime())->format('Y-m-d')) {
                    $participantGoOut = new ParticipantGoOut();
                    $participantGoOut->setParticipant($participant);
                    $participantGoOut->setGoOut($goOut);

                    $entityManager->persist($participantGoOut);
                    $entityManager->flush();
                }

                $this->addFlash('error', 'The registration date has passed.');
                return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);

            }
            $this->addFlash('error', 'The number of registrations has been exceeded.');

            return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
        }
        $this->addFlash('error', 'You must be logged in to register for an goOut.');
        return $this->redirectToRoute('app_go_out_login', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);

        // return $this->redirectToRoute('error_page');
    }
    

    #[Route('/{id}', name: 'app_participant_go_out_show', methods: ['GET'])]
    public function show(ParticipantGoOut $participantGoOut): Response
    {
        return $this->render('participant_go_out/show.html.twig', [
            'participant_go_out' => $participantGoOut,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_participant_go_out_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ParticipantGoOut $participantGoOut, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ParticipantGoOutType::class, $participantGoOut);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_participant_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant_go_out/edit.html.twig', [
            'participant_go_out' => $participantGoOut,
            'form' => $form,
        ]);
    }

    #[Route('/user/remove/{id}', name: 'app_participant_go_out_delete', methods: ['GET', 'POST'])]
    public function remove(EntityManagerInterface $entityManager, GoOut $goOut, ParticipantRepository $participantRepository): Response
    {
        // Check if the user is authenticated
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login'); // Redirect to the login page
        }
    
        /** @var User $user */
        $user = $this->getUser();
        $user->getParticipant();
    
        if ($user) {
            // Find and remove the ParticipantGoOut entity for the given GoOut and Participant
            $participantGoOut = $entityManager->getRepository(ParticipantGoOut::class)->findOneBy([
                'participant' => $user,
                'goOut' => $goOut,
            ]);
    
            if ($participantGoOut) {
                $entityManager->remove($participantGoOut);
                $entityManager->flush();
    
                return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
            }
        }

        return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
    }
    
}
