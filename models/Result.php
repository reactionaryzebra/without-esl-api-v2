<?php
class Result{
  private $conn;
  private $table_name = "results";

  public $season;
  public $home_team;
  public $away_team;
  public $home_team_goals;
  public $away_team_goals;
  public $outcome;

  public function __construct($db){
    $this->conn = $db;
  }

  public function create() {
    $terms = json_decode(json_encode($this), true);
    $stmt = pg_insert($this->conn, $this->table_name, $terms);
  }

}
 ?>
