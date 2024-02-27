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
            // Vérifier si la sortie est expirée
            $endDateTime = $goOut->getStartDateTime();
            $duration = $goOut->getDuration();
            $interval = new \DateInterval('PT' . $duration . 'H');
            $endDateTime->add($interval);
            $isExpired = $endDateTime < (new \DateTime())->modify('-1 month');

            if ($isExpired) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->findOneBy(['libelle' => 'Archivée']));
                $this->entityManager->persist($goOut);

            }
        }

        $this->entityManager->flush();
    }
}