<?php

namespace ExamplePlugin;

interface SqlQuerysInterface {

    public function selectItem( string $query, array $args );
    public function insertItem( string $query, array $args );

}