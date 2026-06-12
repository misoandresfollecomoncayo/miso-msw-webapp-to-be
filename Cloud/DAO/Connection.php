<?php

use Cloud\Engine\PHP\MySQL;

class Connection extends MySQL\CloudEngineMySQLConnection {
    
    public function __construct() {
        parent::__construct("localhost", DATABASE_USER, DATABASE_PASSWORD, DATABASE_NAME);
    }
    
}