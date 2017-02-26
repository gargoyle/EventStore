<?php

namespace Pmc\EventStore\Driver\MySQL;

use Pmc\EventStore\Exception\DatabaseConnectionFailure;

/**
 * Wrapper around MySQLi.
 * 
 * @todo implement connection testing and retry logic.
 * 
 * @author Gargoyle <g@rgoyle.com>
 */
class ConnectionHandler
{
    private $password;
    private $username;
    private $dbname;
    private $host;

    public function __construct(string $host, string $dbname, string $username, string $password)
    {
        $this->host = $host;
        $this->dbname = $dbname;
        $this->username = $username;
        $this->password = $password;
    }

    public function getConnection(): \mysqli
    {
        $mysqli = mysqli_init();
        $mysqli->options(MYSQLI_OPT_INT_AND_FLOAT_NATIVE, 1);
        $mysqli->options(MYSQLI_OPT_CONNECT_TIMEOUT, 5);
        
        $mysqli->real_connect(
                $this->host,
                $this->username,
                $this->password,
                $this->dbname);
        if ($mysqli->connect_errno) {
            throw new DatabaseConnectionFailure(sprintf(
                    "Failed to connect to MySQL Server: (%s) %s",
                    $mysqli->connect_errno,
                    $mysqli->connect_error
                    ));
        }
        
        $mysqli->set_charset('utf8');
        return $mysqli;
    }
    
    
}
