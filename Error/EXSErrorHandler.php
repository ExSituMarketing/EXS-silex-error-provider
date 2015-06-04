<?php

namespace EXS\ErrorProvider\Error;

use Symfony\Component\Debug\Exception\FlattenException;
use EXS\ErrorProvider\Services\Loggers\ExceptionLoggerService;

/**
 * Description of ExceptionLoggerInterface
 *
 * Part of the Error LoggerBundle
 *
 * Listens for any exception and persists it via doctrine entity
 *
 * Created      04/09/2015
 * @author      Charles Weiss
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class EXSErrorHandler
{
    /**
     * Register set_error_handler and register_shutdown_function on first event.
     * @param voids
     */
    public static function register()
    {
        // Set the error handler
        set_error_handler(array(__CLASS__, 'errorToException'));

        //to catch fatal error, set a shutdown function
        register_shutdown_function(array(__CLASS__, 'errorAtShutdown'));
    }

    /**
     * Catch fatal error.
     * (used with register_shutdown_function)
     * @return void
     */
    public static function errorAtShutdown()
    {
        $error = error_get_last();
        if (isset($error)) {
            self::onAnyException(new \ErrorException($error['message'], $error['type'], 1, $error['file'], $error['line']));
        }
    }

    /**
     * Raise error on any php error found.
     *
     * @param  type            $level
     * @param  type            $message
     * @param  type            $file
     * @param  type            $line
     * @param  type            $context
     * @throws \ErrorException
     */
    public static function errorToException($level = 0, $message = '', $file = null, $line = 0, $context = array())
    {
        switch ($level) {
            case E_NOTICE:
            case E_USER_NOTICE:
                $errors = "Notice";
                break;
            case E_WARNING:
            case E_USER_WARNING:
                $errors = "Warning";
                break;
            case E_ERROR:
            case E_USER_ERROR:
                $errors = "Fatal Error";
                break;
            default:
                $errors = "Unknown Error";
                break;
        }

        error_log(sprintf("PHP %s: %s in %s on line %d", $errors, $message, $file, $line));

        //save any error.
        return self::onAnyException(new \ErrorException($message, 1, $level, $file, $line));
    }

    /**
     * Use to log any controlled exception.
     *
     * @param  \Exception   $exception
     * @return Exception5xx
     */
    public static function onAnyException(\Exception $exception,ExceptionLoggerFileService $loggerService=NULL)
    {
        $exception = FlattenException::create($exception);

        return self::logException($exception,$loggerService);
    }

    /**
     * Log the actual exception
     * Should catch any exception so we dont end up in recursion hell
     * Anything missed *should* be in the php error log file if set
     * @param FlattenException $exception
     * @param Request          $request
     */
    public static function logException(FlattenException $exception,ExceptionLoggerFileService $loggerService=NULL)
    {
        try {
            if($loggerService){
                $loggerService->persistException($exception);
            }
        } catch (\Exception $ex) {
            // Silence
        }
    }
}
