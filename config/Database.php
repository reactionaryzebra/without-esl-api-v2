<?php
class Database{
    public $conn;

    // get the database connection
    public function getConnection(){
        $this->conn = pg_connect(getenv("DATABASE_URL"));

        return $this->conn;
    }
}
 ?>
