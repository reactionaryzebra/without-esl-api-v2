<?php
class Database{
    public $conn;

    // get the database connection
    public function getConnection(){
        $this->conn = pg_connect("host=localhost port=5432 dbname=match_results");

        return $this->conn;
    }
}
 ?>
