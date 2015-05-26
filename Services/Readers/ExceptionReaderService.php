<?php

namespace EXS\ErrorProvider\Services\Readers;


/**
 * Description of ExceptionReaderService
 *
 * Created 20-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ExceptionReaderService
{

    /**
     * The mysql logger
     * @var EXS\ErrorProvider\Services\Readers\ExceptionLoggerMysqlService
     */
    protected $logger;

    /**
     * The location of the log file
     * @var string
     */
    protected $logfile = '';

    /**
     * The constructor
     * @param \EXS\ErrorProvider\Services\Readers\ExceptionLoggerMysqlService $logger
     * @param type $logfile
     */
    public function __construct(ExceptionLoggerMysqlService $logger, $logfile = '')
    {
        $this->logger = $logger;
        $this->logfile = $logfile;
    }

    public function run()
    {
        $this->boo();
    }

    public function boo()
    {
        $file = new \SplFileObject($this->logfile, 'r+');
        if ($file->flock(LOCK_EX)) { // do an exclusive lock
            while (!$file->eof()) {
                $line = $file->fgets();
                if (!empty($line)) {
                    $this->logger->persist($line);
                }
            }
            //$file->ftruncate(0);     // truncate file
            $file->flock(LOCK_UN);   // release the lock
        }
    }
}
