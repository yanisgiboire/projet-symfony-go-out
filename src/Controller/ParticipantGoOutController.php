<?php

namespace App\Controller;

use App\Entity\GoOut;
use App\Entity\ParticipantGoOut;
use App\Entity\Status;
use App\Form\ParticipantGoOutType;
use App\Repository\ParticipantGoOutRepository;
use App\Repository\StatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use App\Repository\ParticipantRepository;

#[Route('/participant_goout')]
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
    public function new(EntityManagerInterface $entityManager, GoOut $goOut, ParticipantRepository $participantRepository, StatusRepository $statusRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();
        $participant = $user->getParticipant();
        if ($participant) {

            $participantGoOut = $participantGoOutRepository->findOneBy([
                'participant' => $participant,
                'goOut' => $goOut,
            ]);

            if ($participantGoOut) {
                $this->addFlash('error', 'Vous êtes déjà inscrit à cette sortie.');
                return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
            }

            if (($goOut->getMaxNbInscriptions() > count($goOut->getParticipantGoOuts()))) {
                if ($goOut->getLimitDateInscription()->format('Y-m-d') > (new \DateTime())->format('Y-m-d')) {
                    if ($goOut->getOrganizer() === $participantRepository->find($user->getParticipant())) {
                        $this->addFlash('error', 'Vous ne pouvez pas vous inscrire à une sortie que vous avez créée.');
                        return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
                    }
                    $participantGoOut = new ParticipantGoOut();
                    $participantGoOut->setParticipant($participant);
                    $participantGoOut->setGoOut($goOut);

                    $entityManager->persist($participantGoOut);

                    if (count($goOut->getParticipantGoOuts()) + 1 >= $goOut->getMaxNbInscriptions()) {
                        $goOut->setStatus($statusRepository->findOneBy(['libelle' => Status::STATUS_CLOSED ]));
                    }
                    $entityManager->flush();

                    $this->addFlash('success', 'Vous êtes inscrit à la sortie ' . $goOut->getName() . '.');

                    return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
                }
                $this->addFlash('error', 'La date d\'inscriptions a été dépassé.');
                return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
            }
                $this->addFlash('error', 'Le nombre maximum d\'inscriptions a été atteint.');
                return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
            }

        $this->addFlash('error', 'Vous ne pouvez pas vous inscrire à une sortie si vous n\'êtes pas inscrit.');
        return $this->redirectToRoute('app_login');

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

            $this->addFlash('success', 'Votre inscription a bien été modifiée.');
            return $this->redirectToRoute('app_participant_go_out_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant_go_out/edit.html.twig', [
            'participant_go_out' => $participantGoOut,
            'form' => $form,
        ]);
    }

    #[Route('/remove/{id}', name: 'app_participant_go_out_delete', methods: ['GET', 'POST'])]
    public function remove(EntityManagerInterface $entityManager, GoOut $goOut, ParticipantGoOutRepository $participantGoOutRepository, StatusRepository $statusRepository): Response
    {
        if (!$this->getUser()) {
            return $this->redirectToRoute('app_login');
        }

        /** @var User $user */
        $user = $this->getUser();

        if ($user) {
            $participantGoOut = $participantGoOutRepository->findOneBy(['participant' => $user->getParticipant(), 'goOut' => $goOut]);
            if ($participantGoOut) {
                $entityManager->remove($participantGoOut);
                if (count($goOut->getParticipantGoOuts()) + 1 >= $goOut->getMaxNbInscriptions()) {
                    $goOut->setStatus($statusRepository->findOneBy(['libelle' => Status::STATUS_OPENED ]));
                }
                $entityManager->flush();

                $this->addFlash('success', 'Vous vous êtes désinscrit de la sortie ' . $goOut->getName() . '.');
                return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
            }
        }
        $this->addFlash('error', 'Vous ne pouvez pas vous désinscrire d\'une sortie à laquelle vous n\'êtes pas inscrit.');

        return $this->redirectToRoute('app_go_out_show', ['id' => $goOut->getId()], Response::HTTP_SEE_OTHER);
    }
}
