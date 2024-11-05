<?php

namespace App\Infrastructure\Symfony\Command;

use App\UseCase\CreateTeam\Request;
use App\UseCase\CreateTeam\UseCase;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'app:create-team',
    description: 'Creates a new team.'
)]
class CreateTeamCommand extends Command
{
    private UseCase $useCase;

    public function __construct(UseCase $useCase)
    {
        parent::__construct();
        $this->useCase = $useCase;
    }

    protected function configure(): void
    {
        $this
            ->addArgument('name', InputArgument::REQUIRED, 'The name of the team');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $name = $input->getArgument('name');

        try {
            $response = $this->useCase->execute(new Request($name));
            $output->writeln('Team created with ID: ' . $response->getTeam()->getId());
            return Command::SUCCESS;
        } catch (\Exception $e) {
            $output->writeln('Error: ' . $e->getMessage());
            return Command::FAILURE;
        }
    }
}
