<?php

namespace App\Commands;

use App\Domain\UrlDomain;
use App\Repository\UrlRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;

class SendStatisticsCommand extends Command
{
    protected static $defaultName = 'UrlApi:SendStatistics';

    private EntityManagerInterface $entityManager;

    private UrlRepository $urlRepository;

    private ParameterBagInterface $parameterBag;

    public function __construct(EntityManagerInterface $entityManager, UrlRepository $urlRepository, ParameterBagInterface $parameterBag)
    {
        $this->entityManager = $entityManager;

        $this->urlRepository = $urlRepository;

        $this->parameterBag = $parameterBag;

        parent::__construct();
    }

    protected function configure()
    {
        $this->setDescription('Send url statistics to configured endpoint');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $urlDomain = new UrlDomain($this->entityManager, $this->urlRepository);

        $table = new Table($output);

        $table
            ->setHeaders(["url_statistics_remote_address"])
            ->addRow(["url_statistics_remote_address" => $this->parameterBag->get("url_statistics_remote_address")])
        ;

        $table->render();

        return Command::SUCCESS;
    }
}