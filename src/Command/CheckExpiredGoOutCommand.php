<?php

namespace App\Command;

use App\Entity\GoOut;
use App\Service\CheckGoOutStatusService;
use App\Service\StatusService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Doctrine\ORM\EntityManagerInterface;
use App\Entity\Sortie;
use App\Entity\Status;
use SebastianBergmann\Environment\Console;

class CheckExpiredGoOutCommand extends Command
{

    public function __construct(private CheckGoOutStatusService $checkGoOutStatusService)
    {
        parent::__construct();
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

        $this->checkGoOutStatusService->checkGoOutStatus();

        $io->success('Vérification des sorties expirées et réalisées depuis plus d\'un mois terminée.');

        $this->checkGoOutStatusService->updateStatus();

        $io->success('Vérification des status terminée.');

        return Command::SUCCESS;
    }
}
