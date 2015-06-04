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
use \EXS\ErrorProvider\Services\Readers\ExceptionReaderService;

class LogloaderCommand extends Command
{
    protected $service;

    public function __construct($name = '', ExceptionReaderService $service = null)
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
}
