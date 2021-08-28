<?php 

namespace ExamplePlugin\SqlQuerys;
use ExamplePlugin\SqlQuerysInterface\SqlQuerysInterface;

class SqlQuerys implements SqlQuerysInterface extends
{

    function __construct()
    {
        $query = "SELECT * FROM wp_users";
        $args = [];
        $this->selectItem($query, $args);
    }

    private function selectItem( $query, $args )
    {

    }

    private function insertItem( $query, $args )
    {

    }
}
