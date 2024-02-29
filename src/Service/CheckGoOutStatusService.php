<?php

namespace App\Service;

use App\Entity\GoOut;
use App\Entity\ParticipantGoOut;
use App\Entity\Status;
use App\Repository\GoOutRepository;
use App\Repository\ParticipantGoOutRepository;
use Doctrine\ORM\EntityManagerInterface;

class CheckGoOutStatusService
{
    public function __construct(private readonly EntityManagerInterface $entityManager,
                                private ParticipantGoOutRepository $participantGoOutRepository,
                                private GoOutRepository $goOutRepository)
    {
    }

    public function checkGoOutStatus(): void
    {
        $allGoOuts = $this->entityManager->getRepository(GoOut::class)->findAll();

        foreach ($allGoOuts as $goOut) {
            $endDateTime = $goOut->getStartDateTime();
            $duration = $goOut->getDuration();
            $interval = new \DateInterval('PT' . $duration . 'H');
            $endDateTime->add($interval);
            $isExpired = $endDateTime < (new \DateTime())->modify('-1 month');

            if ($isExpired) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_ARCHIVED]));
                $this->entityManager->persist($goOut);

            }
        }

        $this->entityManager->flush();
    }

    public function updateStatus(): void
    {
        $today = new \DateTime();
        $allGoOuts = $this->entityManager->getRepository(GoOut::class)->findAll();


        foreach ($allGoOuts as $goOut) {
            $startDate = $goOut->getStartDateTime();
            $endDate = clone $startDate;
            $endDate->modify('+' . $goOut->getDuration() . ' minutes');

            if ($endDate < $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_PASSED]));
            } elseif ($startDate === $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_ACTIVITY_IN_PROGRESS]));
            } elseif ($goOut->getMaxRegistrations() === count($goOut->getParticipantGoOuts()->toArray()) && $goOut->getStatus()->getLibelle() === Status::STATUS_OPENED) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_CLOSED]));
            }
        }
        $this->entityManager->flush();
    }
}