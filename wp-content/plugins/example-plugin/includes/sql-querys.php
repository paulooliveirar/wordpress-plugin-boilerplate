<?php 

namespace ExamplePlugin\Includes;
use ExamplePlugin\SqlQuerysInterface;

include_once 'sql-querys-interface.php';
class SqlQuerys implements SqlQuerysInterface
{

    function __construct()
    {
        $query = "SELECT * FROM wp_users";
        $args = [];
        $this->selectItem($query, $args);
    }

    public function selectItem( $query, $args )
    {
        return $query;
    }

    public function insertItem( $query, $args )
    {
        return $query;
    }
}
