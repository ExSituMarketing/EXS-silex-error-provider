<?php

namespace EXS\ErrorProvider\Services\Loggers;

use Doctrine\DBAL\Connection;

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
     * The arrays of entries to insert
     * @var array
     */
    protected $rows4xx = array();    
    protected $rows5xx = array();
    
    /**
     * Name of tables to insert error logs
     * @var string
     */
    protected $table4xx = 'trafficfalcon.exception4xx';
    protected $table5xx = 'trafficfalcon.exception5xx';

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

    /**
     * Add row values to array
     * @param object $message
     */
    public function addRow($message = null){
        $decodedMessage = json_decode($message); // decode message
        if($decodedMessage->statusCode >= 400 && $decodedMessage->statusCode < 500) { // split it for 4xx and 5xx
            $this->add4xxRow($decodedMessage);
        } else {
            $this->add5xxRow($decodedMessage);
        }
    }
    
    /**
     * Add 400 exceptions to the array
     * String escape for query excution
     * @param object $decodedMessage
     */
    public function add4xxRow($decodedMessage = null) {
        $arr = array(
            $decodedMessage->statusCode,
            $this->connection->quote($decodedMessage->message, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->requestUrl, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->referrer, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->userAgent, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->remoteIp, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->method, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->queryString, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->hostname, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->request, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->logged, \PDO::PARAM_STR)            
        );
        $this->rows4xx[] = "(".implode(',',$arr).")";     
    }  
    
    /**
     * Add 500 exceptions to the array
     * String escape for query excution
     * @param object $decodedMessage
     */    
    public function add5xxRow($decodedMessage = null) {
        $arr = array(
            $decodedMessage->statusCode,
            $this->connection->quote($decodedMessage->message, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->file, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->line, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->trace, \PDO::PARAM_STR),            
            $this->connection->quote($decodedMessage->requestUrl, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->referrer, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->userAgent, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->remoteIp, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->method, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->queryString, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->hostname, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->request, \PDO::PARAM_STR),
            $this->connection->quote($decodedMessage->logged, \PDO::PARAM_STR)           
        );
        $this->rows5xx[] = "(".implode(',',$arr).")";   
    }     

    /**
     * Process exception db update
     * @param 
     */
    public function processDbUpdate() {    
        if(!empty($this->rows4xx)) { // there are 4xx exceptions
            $this->process4xxUpdate();
            $this->rows4xx = array();
        }
        
        if(!empty($this->rows5xx)) { // there are 5xx exceptions
            $this->process5xxUpdate();
            $this->rows5xx = array();
        }        
    }    
    
    /**
     * Create an insert query for 4xx exceptions.
     * table name is defined as a global variable.
     */
    public function process4xxUpdate() {        
        $query = "INSERT INTO ". $this->table4xx ." (statusCode, message, requestUrl, referrer, userAgent, remoteIp, method, queryString, hostname, request, logged) VALUES ";
        $query .= implode(',', $this->rows4xx); 
        $this->persist($query);
    }
    
    /**
     * Create an insert query for 5xx exceptions.
     * table name is defined as a global variable.
     */    
    public function process5xxUpdate() {        
        $query = "INSERT INTO ". $this->table5xx ." (statusCode, message, file, line, trace, requestUrl, referrer, userAgent, remoteIp, method, queryString, hostname, request, logged) VALUES ";
        $query .= implode(',', $this->rows5xx); 
        $this->persist($query);       
    }    
    
    /**
     * Excute query 
     * @param type $query
     */
    public function persist($query){
        if(!empty($query)){
            $sth = $this->connection->prepare($query);
            $sth->execute();
        }
    }    
}
