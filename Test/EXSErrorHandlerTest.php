<?php

namespace EXS\TrafficFalcon\Tests\Error;

use PHPUnit_Framework_TestCase;
use EXS\TrafficFalcon\Error\EXSErrorHandler;
/**
 * Description of EXSErrorHandlerTest
 *
 * Created 10-Apr-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class EXSErrorHandlerTest extends PHPUnit_Framework_TestCase
{
    public function testErrorAtShutdown()
    {
        //trigger_error('sample PHP Notice', E_USER_NOTICE);
        trigger_error('sample PHP Fatal', E_USER_ERROR);
        $this->assertEmpty(EXSErrorHandler::errorAtShutdown());
    }
}
