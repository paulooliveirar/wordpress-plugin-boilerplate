<?php 

namespace ExamplePlugin\Includes\QueryBuild;

class Connection
{
    
    private $conn;
    private $prefix;

    function __construct()
    {
        $this->setConnection();
    }

    public function setConnection()
    {
        global $wpdb;
        $this->conn = $wpdb;
        $this->prefix = $wpdb->prefix;
    }

    public function getConnection()
    {
        return $this->conn;
    }

    public function find(string $name, array $args, string $type)
    {
        // OBJECT – result will be output as an object.
        // ARRAY_A – result will be output as an associative array.
        // ARRAY_N – result will be output as a numerically indexed array.
        $type = $type ?? 'ARRAY_A';

        return $this->conn->get_results( "SELECT * FROM {$this->prefix}$name", $type);
        //$sql = $wpdb->prepare( 'query' , value_parameter[, value_parameter ... ] );
    }

    public function insert($name, $args, $type){

    }

    public function update($name, $args, $type){

    }

    function __destruct()
    {
        
    }
}
