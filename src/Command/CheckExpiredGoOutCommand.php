<?php

namespace App\Command;

use App\Service\CheckGoOutStatusService;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

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
        $this->checkGoOutStatusService->updateStatus();

        $io->success('Vérification des sorties et mise à jour des status.');

        return Command::SUCCESS;
    }
}
