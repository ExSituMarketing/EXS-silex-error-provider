<?php

namespace EXS\ErrorProvider\Controllers;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\Exception\MethodNotAllowedHttpException;
use Symfony\Component\HttpKernel\Exception\GoneHttpException;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * Description of DebugController
 *
 * Error Controller
 * Ouput errors (useful for testing error handling
 *
 * Created      04/10/2015
 * @author      Charles Weiss
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ErrorController
{

    /**
     * The twig templating class
     * @var Twig_Environment
     */
    protected $twig;

    /**
     * The url service
     * @var object UrlService
     */
    protected $UrlService;

    public function __construct(\Twig_Environment $twig)
    {
        $this->twig = $twig;
    }

    /**
     * The response action
     * @return Response
     */
    public function responseAction($slug = '')
    {
        switch ($slug) {
            case 'silent':
                return $this->silent();
            case 'warning':
                return $this->phpWarning();
            case 'deprecated':
                return $this->phpDeprecated();
            case 'fatal':
                return $this->phpFatal();
            case 'notice':
                return $this->phpNotice();
            case 403:
                throw new AccessDeniedHttpException('403 Test');
            case 404:
                throw new NotFoundHttpException('404 Test');
            case 405:
                throw new MethodNotAllowedHttpException(array());
            case 410:
                throw new GoneHttpException('410 Test');
            case 500:
            default:
                throw new \Exception('500 Test');
        }
    }

    public function silent()
    {
        try {
            throw new \Exception("This exception is caught and logged without breaking the app.");
        } catch (\Exception $e) {
            // be silent
        }

        return new Response($this->twig->render('debug.html', array('error' => 'test')));
    }

    public function phpFatal()
    {
        trigger_error('sample PHP Fatal', E_USER_ERROR);
    }

    public function phpNotice()
    {
        trigger_error('sample PHP Notice', E_USER_NOTICE);
    }

    public function phpWarning()
    {
        trigger_error('sample PHP Warning', E_USER_WARNING);
    }

    public function phpDeprecated()
    {
        trigger_error('sample PHP Deprecated', E_USER_DEPRECATED);
    }
}
