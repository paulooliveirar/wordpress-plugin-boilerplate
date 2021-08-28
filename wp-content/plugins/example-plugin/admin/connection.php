<?php

namespace ExamplePlugin\Admin\Connection;

use ExamplePlugin\IncludesExamplePluginActivator\ExamplePluginActivator;

require_once (dirname(__FILE__) . '/../includes/class-example-plugin-activator.php');

class Connection extends ExamplePluginActivator{
    function __construct()
    {
       
    }

    public function getConnect(){
        return $this->getConnection();
    }
}