<?php

namespace App\Service;

use App\Entity\GoOut;
use App\Entity\Status;
use Doctrine\ORM\EntityManagerInterface;

class CheckGoOutStatusService
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
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

//    public function updateStatus(): void
//    {
//        // si la date de début + la durée alors passe moi en terminé
//        // si la date de début est égal à la date d'aujourd'hui alors passe moi en cours
//        // Si la date limite d'inscription est inférieur à la date de d'aujourd'hui alors passe moi en ouvert
//        // si la date limite d'inscription est supérieur alors passe moi en clôturé
//        // si la date de début est inférieur à la date d'aujourd'hui alors passe moi en passée
//        // si la date de début est supérieur à la date d'aujourd'hui alors passe moi en créée
//    }

    public function updateStatus(): void
    {
        $today = new \DateTime();
        $allGoOuts = $this->entityManager->getRepository(GoOut::class)->findAll();

        foreach ($allGoOuts as $goOut) {
            $startDate = $goOut->getStartDateTime();
            $endDate = clone $startDate;
            $endDate->modify('+' . $goOut->getDuration() . ' minutes');

            if ($endDate <= $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_PASSED]));
            } elseif ($startDate === $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_ACTIVITY_IN_PROGRESS]));
            } elseif ($goOut->getLimitDateInscription() > $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_OPENED]));
            } elseif ($goOut->getLimitDateInscription() < $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_CLOSED]));
            } elseif ($startDate < $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_PASSED]));
            } elseif ($startDate > $today) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => Status::STATUS_CREATED]));
            }
        }

        $this->entityManager->flush();
    }
}