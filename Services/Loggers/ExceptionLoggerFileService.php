<?php

namespace EXS\ErrorProvider\Services\Loggers;

use Symfony\Component\HttpKernel\Exception\FlattenException;
use EXS\RequestProvider\Services\RequestService;
use EXS\ErrorProvider\Services\Utils\Flattener;
use EXS\ErrorProvider\Entities\Exception5xx;
use EXS\ErrorProvider\Entities\Exception4xx;

/**
 * Description of ExceptionLoggerService
 *
 * Created 19-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ExceptionLoggerFileService
{

    /**
     * The current request
     * @var EXS\RequestProvider\Services\RequestService
     */
    protected $request;

    /**
     * The location of the log file
     * @var string
     */
    protected $logfile = '';

    /**
     * The constructor
     * @param RequestServiceInterface $requestService
     * @param string $logfile
     */
    public function __construct(RequestService $RequestService, $logfile = '')
    {
        $this->request = $RequestService->getRequest();
        $this->logfile = $logfile;
    }

    /**
     * Write the exception to file
     * @param FlattenException $exception
     */
    public function persistException(FlattenException $exception)
    {
        echo 'hi';
        $this->writeLog($this->buildString($exception));
        return;
    }

    public function writeLog($line = '')
    {
        if (is_dir(dirname($this->logfile))) {
            file_put_contents($this->logfile, $line . "\n", FILE_APPEND | LOCK_EX);
        }
        return true;
    }

    public function buildString(FlattenException $exception)
    {
        if ($this->getHttpStatusCode($exception) == 4) {
            $eLog = $this->build4xxString();
        } else {
            $eLog = $this->build5xxString($exception);
        }
        $eLog = $this->appendGeneric($exception, $eLog);

        $encoded = $this->encodeException($eLog);

        return $encoded;
    }

    public function encodeException($eLog = null)
    {
        $encode = json_encode($eLog);
        return $encode;
    }

    public function build5xxString(FlattenException $exception)
    {
        $elog = new Exception5xx;
        $elog->setFile($exception->getFile());
        $elog->setLine($exception->getLine());
        $elog->setTrace($this->flattenTrace($exception));
        return $elog;
    }

    public function build4xxString()
    {
        $elog = new Exception4xx;
        return $elog;
    }

    public function appendGeneric(FlattenException $exception, $elog)
    {
        $elog->setStatusCode($exception->getStatusCode());
        $elog->setMessage($exception->getMessage());
        $elog->setRequestUrl($this->request->getRequestUri());
        $elog->setReferrer($this->request->server->get('HTTP_REFERER'));
        $elog->setUserAgent($this->request->server->get('HTTP_USER_AGENT'));
        $elog->setRemoteIp($this->request->server->get('REMOTE_ADDR'));
        $elog->setMethod($this->request->server->get('REQUEST_METHOD'));
        $elog->setQueryString($this->request->server->get('QUERY_STRING'));
        $elog->setHostname(php_uname('n'));
        $elog->setRequest(Flattener::flattenArrayToString($this->request->server->all()));
        $elog->setLogged($this->getDate());
        return $elog;
    }

    public function getDate()
    {
        $d = new \DateTime('now');
        return $d->format('Y-m-d H:i:s');
    }

    /**
     * Get the proper status code this exception should be logged under
     *
     * @param FlattenException $exception
     * @return integer
     */
    public function getHttpStatusCode(FlattenException $exception)
    {
        if (($exception->getStatusCode() >= 400) && ($exception->getStatusCode() < 500)) {
            return 4;
        } else {
            return 5;
        }
    }

    /**
     * Flatten the trace
     * @param FlattenException $exception
     * @return string
     */
    public function flattenTrace(FlattenException $exception)
    {
        $tr = '';
        foreach ($exception->getTrace() as $trace) {
            $traceMessage = sprintf('  at %s line %s', $trace['file'], $trace['line']);
            $tr .= $traceMessage . "\n";
        }
        return $tr;
    }
}
