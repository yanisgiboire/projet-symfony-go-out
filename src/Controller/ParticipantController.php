<?php

namespace App\Controller;

use App\Entity\Participant;
use App\Form\ParticipantType;
use App\Repository\ParticipantRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

#[Route('/participant')]
class ParticipantController extends AbstractController
{
    #[Route('/{id}/edit', name: 'app_participant_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Participant $participant, EntityManagerInterface $entityManager, SluggerInterface $slugger, Filesystem $filesystem): Response
    {
        $form = $this->createForm(ParticipantType::class, $participant);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            /** @var UploadedFile $brochureFile */
            $imageFile = $form->get('imageProfileName')->getData();
            if ($imageFile) {
                // Supprimer l'ancienne image si elle existe
                $oldFilename = $participant->getImageProfileName();
                if ($oldFilename) {
                    $oldFilePath = $this->getParameter('profile_image_directory') . '/' . $oldFilename;
                    if ($filesystem->exists($oldFilePath)) {
                        $filesystem->remove($oldFilePath);
                    }
                }

                $originalFilename = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $imageFile->guessExtension();
                try {
                    $imageFile->move(
                        $this->getParameter('profile_image_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    $this->addFlash('error', 'Erreur avec l\'image !');
                }
                $participant->setImageProfileName($newFilename);
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_user_show', ['id' => $participant->getUser()->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('participant/edit.html.twig', [
            'participant' => $participant,
            'form' => $form,
        ]);
    }
}
