<?php

namespace Auth\Command\Ad\Sync;

use Auth\Service\BisDir;
use Bis\Service\BisPersonView;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class BisToLDAPCommand extends Command
{

    /**
     * @var BisDir
     */
    private $bisDir;

    /**
     * @var BisPersonView
     */
    private $bisPersonView;

    /**
     * AdResetAccountCommand constructor.
     *
     * @param BisPersonView $bisPersonView
     * @param BisDir        $bisDir
     */
    public function __construct(BisPersonView $bisPersonView, BisDir $bisDir)
    {
        $this->bisDir = $bisDir;
        $this->bisPersonView = $bisPersonView;
        parent::__construct();
    }

    /**
     * Configures the current command.
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     */
    protected function configure()
    {
        $this->setName('ad:sync:bis-to-ldap')
            ->setDescription('Sync the BIS account with LDAP account')
            ->addArgument('email', InputArgument::REQUIRED, 'User email [@enabel.be]?');
    }

    /**
     * Executes the current command.
     *
     * This method is not abstract because you can use this class
     * as a concrete class. In this case, instead of defining the
     * execute() method, you set the code to execute by passing
     * a Closure to the setCode() method.
     *
     * @param InputInterface  $input
     * @param OutputInterface $output
     *
     * @return int 0 if everything went fine, or an error code
     *
     * @throws \Symfony\Component\Console\Exception\InvalidArgumentException
     * @throws \RuntimeException
     * @throws \Adldap\AdldapException
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $bisPersonView = $this->bisPersonView
            ->getUser($input->getArgument('email'));

        if (null !== $bisPersonView) {
            $responses = $this->bisDir
                ->synchronizeFromBis($bisPersonView);

            $output->writeln('User: ' . $bisPersonView->getEmail() . ' - ' . $bisPersonView->getDisplayName());
            foreach ($responses as $response) {
                $output->writeln('Message: ' . $response->getMessage());
                $output->writeln('Status: ' . $response->getStatus());
                $output->writeln('Type: ' . $response->getType());
                $output->writeln('Data: ' . json_encode($response->getData()));
            }
        }
        $output->writeln('<error>User: ' . $input->getArgument('email') . ' not found!</error>');

        return 0;
    }
}
