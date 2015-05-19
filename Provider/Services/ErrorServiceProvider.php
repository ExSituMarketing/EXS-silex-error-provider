<?php

namespace EXS\ErrorProvider\Providers\Services;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use EXS\TrafficFalcon\Services\Loggers\ExceptionLoggerService;
use EXS\TrafficFalcon\Error\EXSErrorHandler;

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
            return new ExceptionLoggerService($app['db'],$app['tf.serv.request']);
        });
        // Log any fatal errors
        $app->error(function (\Exception $e, $code) use ($app) {
        // Need to comment this out as we cant test for both envs simultaneously
        //    if ($app['debug'] == true) {
        //        return;
        //    }
            EXSErrorHandler::onAnyException($e,$app['exs.serv.exception.logger']);
        //    return $app->redirect($app['param.fallbackUrl']);
        });
    }
}
