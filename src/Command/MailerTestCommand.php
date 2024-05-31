<?php

namespace App\Command;

use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

#[AsCommand('app:mailer:test', 'Sends test emails via Symfony Mailer.')]
class MailerTestCommand extends Command
{
    public function __construct(private readonly MailerInterface $mailer)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('amount', InputArgument::OPTIONAL, 'The amount of test emails to be sent.', 10)
            ->addOption('from', mode: InputOption::VALUE_OPTIONAL, description: 'The sender address.', default: 'from@example.com')
            ->addOption('to', mode: InputOption::VALUE_OPTIONAL, description: 'The recipient address.', default: 'to@example.com')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        for ($i = 1; $i <= $input->getArgument('amount'); ++$i) {
            $email = (new Email())
                ->from($input->getOption('from'))
                ->to($input->getOption('to'))
                ->subject('Test #'.$i)
                ->text('Test #'.$i)
            ;

            $this->mailer->send($email);
        }

        return Command::SUCCESS;
    }
}
