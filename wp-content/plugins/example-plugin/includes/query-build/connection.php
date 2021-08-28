<?php 

namespace ExamplePlugin\Includes\QueryBuild\Connection;

class Connection
{
    
    private array $GLOBALS;
    private $conn;

    function __construct()
    {
        global $wpdb;
        $this->conn = $wpdb;
    }

    function setConnection()
    {
        return;
    }

    function getConnection()
    {
        return $this->conn;
    }

    function __destruct()
    {
        
    }
}
