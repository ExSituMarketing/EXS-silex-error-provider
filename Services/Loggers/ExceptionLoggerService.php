<?php

namespace EXS\ErrorProvider\Services\Loggers;

use Symfony\Component\HttpKernel\Exception\FlattenException;
use EXS\RequestProvider\Services\RequestService;
use EXS\ErrorProvider\Services\Utils\Flattener;

/**
 * Description of ExceptionLoggerService
 *
 * Created 19-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ExceptionLoggerService
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
        $this->writeLog($this->buildString($exception));
        return;
    }

    /**
     * Write the exception to log file
     * @param string $line
     * @return boolean
     */
    public function writeLog($line = '')
    {
        if (is_dir(dirname($this->logfile))) {
            file_put_contents($this->logfile, $line . "\n", FILE_APPEND | LOCK_EX);
        }
        return true;
    }

    /**
     * Turn the exception to a helpful string
     * The string is formed from an array that has been json_encoded
     * @param FlattenException $exception
     * @return sring
     */
    public function buildString(FlattenException $exception)
    {
        if ($this->getHttpStatusCode($exception) == 4) {
            $parts = $this->build4xxString($exception);
        } else {
            $parts = $this->build5xxString($exception);
        }

        return serialize($parts);
    }

    /**
     * Assemble the 5xx specific debug information
     * @param FlattenException $exception
     * @return array
     */
    public function build5xxString(FlattenException $exception)
    {
        $parts = [];
        $parts['statusCode'] = $exception->getStatusCode();
        $parts['file'] = $exception->getFile();
        $parts['line'] = $exception->getLine();
        $parts['message'] = $exception->getMessage();
        $parts['trace'] = $this->flattenTrace($exception);
        return $this->appendGeneric($parts);
    }

    /**
     * Assemble the 4xx specific debug information
     * @param FlattenException $exception
     * @return array
     */
    public function build4xxString(FlattenException $exception)
    {
        $parts = [];
        $parts['statusCode'] = $exception->getStatusCode();
        $parts['message'] = $exception->getMessage();
        return $this->appendGeneric($parts);
    }

    /**
     * Assemble the generic debug information
     * @param array the specific exception pieces
     * @return array
     */
    public function appendGeneric($parts = array())
    {
        $parts['requestUrl'] = $this->request->getRequestUri();
        $parts['referrer'] = $this->request->server->get('HTTP_REFERER');
        $parts['userAgent'] = $this->request->server->get('HTTP_USER_AGENT');
        $parts['remoteIp'] = $this->request->server->get('REMOTE_ADDR');
        $parts['method'] = $this->request->server->get('REQUEST_METHOD');
        $parts['queryString'] = $this->request->server->get('QUERY_STRING');
        $parts['hostname'] = php_uname('n');
        $parts['request'] = Flattener::flattenArrayToString($this->request->server->all());
        $parts['logged'] = $this->getDate();
        return $parts;
    }

    /**
     * Friendly date format
     * @return string
     */
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
