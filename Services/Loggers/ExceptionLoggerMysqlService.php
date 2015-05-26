<?php

namespace EXS\ErrorProvider\Services\Loggers;

use Doctrine\DBAL\Connection;
//use EXS\ErrorProvider\Entities\Exception5xx;
//use EXS\ErrorProvider\Entities\Exception4xx;

/**
 * Description of ExceptionLoggerService
 *
 * Created 21-May-2015
 * @author Charles Weiss <charlesw@ex-situ.com>
 * @copyright   Copyright 2015 ExSitu Marketing.
 */
class ExceptionLoggerMysqlService
{

    /**
     * The database connection
     * @var EXS\ErrorProvider\Services\Loggers\Connection
     */
    protected $connection;

    /**
     * The constructor
     * @param \EXS\ErrorProvider\Services\Loggers\Connection $connection
     */
    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

}
