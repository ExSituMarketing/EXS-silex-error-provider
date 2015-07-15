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
     * @var EXS\ErrorProvider\Services\Loggers\ExceptionLoggerMysqlService
     */
    protected $logger;

    /**
     * The location of the log file
     * @var string
     */
    protected $logfile;
    
    /**
     * Array size limit before logging them to db.
     * @var int
     */    
    protected $threshold;

    /**
     * The constructor
     * @param \EXS\ErrorProvider\Services\Loggers\ExceptionLoggerMysqlService $logger
     * @param type $logfile
     */
    public function __construct(\EXS\ErrorProvider\Services\Loggers\ExceptionLoggerMysqlService $logger, $logfile = '', $threshold = 2000)
    {
        $this->logger = $logger;
        $this->logfile = $logfile;
        $this->threshold = $threshold;
    }

    /** 
     * Run exception log handler
     * Called from console command
     */
    public function run()
    {
        $this->readExceptionLog();
    }

    /**
     * Read exception file, then update DB.
     * Trancate exception file once all done.
     */
    public function readExceptionLog()
    {
        $file = new \SplFileObject($this->logfile, 'r+');
        if ($file->flock(LOCK_EX)) { // do an exclusive lock
            $inx = 0;
            while (!$file->eof()) {    
                $line = trim($file->fgets());
                if (!empty($line)) {
                    $this->logger->addRow($line); // add line to the array
                    $inx = $this->checkForUpdate($inx); // check if the array need to be processed.
                }                
            }
            $this->logger->processDbUpdate(); // take care of left overs in the array.
            $file->ftruncate(0);     // truncate file
            $file->flock(LOCK_UN);   // release the lock
        }
    }
    
    /**
     * Check how many exception messages have been red.
     * If over the limit, update db then empty arrays 
     * @param int $inx
     * @return int
     */
    public function checkForUpdate($inx = 0)
    {
        $inx++;
        if($inx > $this->threshold) { // over the limit
            $this->logger->processDbUpdate();
            $inx = 0; // reset the counter
        }        
        return $inx;
    }
    
}
