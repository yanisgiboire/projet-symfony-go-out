<?php

namespace App\Command;

use App\Entity\GoOut;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sortie;
use App\Entity\Status;

class CheckExpiredGoOutCommand extends Command
{
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();

        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:check-expired-go-outs')
            ->setDescription('Check for expired and over-a-month old go-outs');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        // Logique pour vérifier les sorties expirées et celles réalisées depuis plus d'un mois
        $allGoOuts = $this->entityManager->getRepository(GoOut::class);
        
        foreach ($allGoOuts as $goOut) {
            // Vérifier si la sortie est expirée
            $endDateTime = clone $goOut->getStartDateTime();
            $endDateTime->add($goOut->getDuration());
            $isExpired = $endDateTime < (new \DateTime())->modify('-1 month');
    
            if ($isExpired) {
                $goOut->setStatus($this->entityManager->getRepository(Status::class)->find(2));
            }
        }


        $io->success('Vérification des sorties expirées et réalisées depuis plus d\'un mois terminée.');

        return Command::SUCCESS;
    }
}
