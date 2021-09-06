<?php

namespace ExamplePlugin\Admin;

//use ExamplePlugin\IncludesExamplePluginActivator\ExamplePluginActivator;

//require_once (dirname(__FILE__) . '/../includes/class-example-plugin-activator.php');

use ExamplePlugin\Includes\QueryBuild\Connection as Conn;

require_once dirname(__FILE__) .  '/../includes/query-build/connection.php';

class Connection{

    public $wpdb;
    private $table_name;

    public function __construct(){
         $this->wpdb = new Conn();
    }

    public function getConnect(){
        return $this->wpdb->getConnection();
    }

    public function insert(string $table_name, array $args, string $type_return){
        return $this->wpdb->insert($table_name, $args, $type_return);
    }

    public function update(string $table_name, array $args, string $type_return){
        return $this->wpdb->update($table_name, $args, $type_return);
    }

    public function find(string $table_name, array $args, string $type_return){
        return $this->wpdb->find($table_name, $args, $type_return);
    }
}