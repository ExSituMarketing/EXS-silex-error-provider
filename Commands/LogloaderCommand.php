<?php

namespace EXS\ErrorProvider\Commands;

/**
 * Description of LogloaderCommand
 *
 * Created 20-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class LogloaderCommand extends Command
{
    protected $service;

    public function __construct($name = '', $service = null)
    {
        $this->service = $service;
        parent::__construct($name);
    }

    protected function configure()
    {
        $this->setName('exs:log:exceptions')
            ->setDescription('Reads exceptions from file and loads them in db.')
            ->setHelp('');
    }

    public function execute(InputInterface $input, OutputInterface $output)
    {
        $this->service->run();
    }

    /**
     * {@inheritdoc}
     */
    protected function createDefinition()
    {
//        return new InputDefinition(array(
//            new InputArgument('namespace', InputArgument::OPTIONAL, 'The namespace name'),
//            new InputOption('xml', null, InputOption::VALUE_NONE, 'To output list as XML'),
//            new InputOption('raw', null, InputOption::VALUE_NONE, 'To output raw command list'),
//            new InputOption('format', null, InputOption::VALUE_REQUIRED, 'To output list in other formats', 'txt'),
//        ));
    }
}
