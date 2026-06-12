<?php

namespace Cloud\Engine\PHP\MySQL;

class CloudEngineMySQLConnection {
    
    private $connection;

    public function __construct($host, $user, $password, $database) {
        $this->connection = mysqli_connect($host, $user, $password, $database);
        mysqli_set_charset($this->connection, "utf8");
    }

    public function getConnection() {
        return $this->connection;
    }    
    
    public function close() {
        mysqli_close($this->connection);
    }
    
}
