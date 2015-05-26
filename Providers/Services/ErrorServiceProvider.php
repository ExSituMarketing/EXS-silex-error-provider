<?php

namespace EXS\ErrorProvider\Providers\Services;

use Pimple\ServiceProviderInterface;
use Pimple\Container;
use EXS\ErrorProvider\Services\Loggers\ExceptionLoggerFileService;
use EXS\ErrorProvider\Services\Readers\ExceptionReaderService;
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
        // Register the file logger
        $app['exs.serv.exception.file.logger'] = ( function ($app) {
            return new ExceptionLoggerFileService($app['exs.serv.request'] , $app['logs.file.exceptions']);
        });

        // Register the mysql logger
        $app['exs.serv.exception.mysql.logger'] = ( function ($app) {
            return new ExceptionLoggerMysqlService($app['db']);
        });

        // Register the reader : REQUIRES the mysql connection
        $app['exs.serv.exception.reader'] = ( function ($app) {
            return new ExceptionReaderService($app['exs.serv.exception.mysql.logger'] , $app['logs.file.exceptions']);
        });

        // Log any fatal errors
        $app->error(function (\Exception $e, $code) use ($app) {
            EXSErrorHandler::onAnyException($e,$app['exs.serv.exception.file.logger']);
        });
    }
}
