<?php 

namespace ExamplePlugin\IncludesExamplePluginActivator;

use ExamplePlugin\Includes\QueryBuild\Connection;

require_once (dirname(__FILE__) . '/query-build/connection.php');

class ExamplePluginActivator extends Connection
{
    function __construct()
    {
        
    }

    public function getConnections()
    {
        return $this->getConnection();
    }

    function __destruct()
    {
        
    }
}
