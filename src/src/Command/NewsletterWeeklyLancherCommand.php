<?php

namespace App\Command;

use Doctrine\Common\Annotations\Annotation\Required;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use App\Core\Services\Mailer\Mailer;

class NewsletterWeeklyLancherCommand extends Command
{
    protected static $defaultName = 'app:newsletter-weekly-lancher';
    /**
     * @var Mailer
     */
    private $mailer;


    /**
     * @Required
     */
    public function setDependecy(Mailer $mailer){
        $this->mailer = $mailer;
    }

    protected function configure()
    {
        $this
            ->setDescription('CronJob for weekly newsletter')
            ->addArgument('force', InputArgument::OPTIONAL, 'force to send')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $arg1 = $input->getArgument('force');

        if ($arg1) {
            $io->note(sprintf('You passed an argument: %s', $arg1));
        }

        $this->mailer->sendMail();

        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
