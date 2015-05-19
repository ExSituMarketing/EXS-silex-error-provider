<?php
namespace EXS\ErrorProvider\Services\Loggers;

use Symfony\Component\HttpKernel\Exception\FlattenException;
/**
 * Description of ExceptionLoggerService
 * 
 * Created 19-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ExceptionLoggerService
{

    //put your code here
    public function persistException(FlattenException $exception)
    {
        var_dump($exception);
    }
}
