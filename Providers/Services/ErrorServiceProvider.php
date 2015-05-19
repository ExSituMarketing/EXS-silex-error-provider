<?php

namespace EXS\ErrorProvider\Providers\Services;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use EXS\ErrorProvider\Services\Loggers\ExceptionLoggerService;
use EXS\ErrorProvider\Error\EXSErrorHandler;

/**
 * Register the service to log errors and define the ErrorHandler
 *
 * Created 4-May-2015
 * @author Damien Demessence
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ErrorServiceProvider implements ServiceProviderInterface
{
    public function register(Container $app)
    {
        $app['exs.serv.exception.logger'] = ( function ($app) {
            return new ExceptionLoggerService($app['exs.serv.request'] , $app['logs.file.exceptions']);
        });
        // Log any fatal errors
        $app->error(function (\Exception $e, $code) use ($app) {
            EXSErrorHandler::onAnyException($e,$app['exs.serv.exception.logger']);
        });
    }
}
