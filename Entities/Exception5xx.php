<?php

namespace EXS\ErrorProvider\Entities;

/**
 * Description of Exception4xx
 *
 * Created 20-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class Exception5xx
{

    /**
     * The record id
     * @var integer
     */
    public $id;

    /**
     * The HTTP status code
     * @var integer
     */
    public $statusCode;

    /**
     * Short description of the exception
     * @var string
     */
    public $message;

    /**
     * The file that threw the exception
     * @var string
     */
    public $file;

    /**
     * The line number where the exception was thrown
     * @var integer
     */
    public $line;

    /**
     * An exploded trace for the exception
     * @var string
     */
    public $trace;

    /**
     * The requested url at time of the exception
     * @var string
     */
    public $requestUrl;

    /**
     * The referring url for the exception
     * @var string
     */
    public $referrer;

    /**
     * The user agent sent by the client who threw the exception
     * @var string
     */
    public $userAgent;

    /**
     * The client's IP
     * @var string
     */
    public $remoteIp;

    /**
     * The HTTP request method
     * @var string
     */
    public $method;

    /**
     * Query string parameters
     * @var string
     */
    public $queryString;

    /**
     * Ther name of the host where the exception occured - uname('n')
     * @var string
     */
    public $hostname;

    /**
     * An exploded array of the full apache server request parameters
     * @var string
     */
    public $request;

    /**
     * When the exception was logged
     * @var datetime
     */
    public $logged;

    public function getId()
    {
        return $this->id;
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getMessage()
    {
        return $this->message;
    }

    public function getFile()
    {
        return $this->file;
    }

    public function getLine()
    {
        return $this->line;
    }

    public function getTrace()
    {
        return $this->trace;
    }

    public function getRequestUrl()
    {
        return $this->requestUrl;
    }

    public function getReferrer()
    {
        return $this->referrer;
    }

    public function getUserAgent()
    {
        return $this->userAgent;
    }

    public function getRemoteIp()
    {
        return $this->remoteIp;
    }

    public function getMethod()
    {
        return $this->method;
    }

    public function getQueryString()
    {
        return $this->queryString;
    }

    public function getHostname()
    {
        return $this->hostname;
    }

    public function getRequest()
    {
        return $this->request;
    }

    public function getLogged()
    {
        return $this->logged;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function setStatusCode($statusCode)
    {
        $this->statusCode = $statusCode;
    }

    public function setMessage($message)
    {
        $this->message = $message;
    }

    public function setFile($file)
    {
        $this->file = $file;
    }

    public function setLine($line)
    {
        $this->line = $line;
    }

    public function setTrace($trace)
    {
        $this->trace = $trace;
    }

    public function setRequestUrl($requestUrl)
    {
        $this->requestUrl = $requestUrl;
    }

    public function setReferrer($referrer)
    {
        $this->referrer = $referrer;
    }

    public function setUserAgent($userAgent)
    {
        $this->userAgent = $userAgent;
    }

    public function setRemoteIp($remoteIp)
    {
        $this->remoteIp = $remoteIp;
    }

    public function setMethod($method)
    {
        $this->method = $method;
    }

    public function setQueryString($queryString)
    {
        $this->queryString = $queryString;
    }

    public function setHostname($hostname)
    {
        $this->hostname = $hostname;
    }

    public function setRequest($request)
    {
        $this->request = $request;
    }

    public function setLogged($logged)
    {
        $this->logged = $logged;
    }
}
