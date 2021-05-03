<?php
include_once '../models/Team.php';

class Table{
  private $conn;
  private $table_name = "results";

  public $season;
  public $excluded_teams;
  public $teams = [];

  public function __construct($db, $season, $excluded_teams) {
    $this->season = $season;
    $this->excluded_teams = $excluded_teams;
    $this->conn = $db;
  }

  private function getResults() {
    $results = pg_query($this->conn, "select * from $this->table_name where season = $this->season");
    return pg_fetch_all($results);
  }

  private function getTeams($results) {
    $all_teams = array();
    foreach ($results as $result) {
      $team = $result['home_team'];
      if (!in_array($team, $this->excluded_teams)){
        $all_teams[$team] = new Team($team);
      }
    }
    return $all_teams;
  }

  private function recordResults($results) {
    foreach ($results as $result) {
      $home_team = $result['home_team'];
      $away_team = $result['away_team'];
      if (in_array($home_team, $this->excluded_teams) || in_array($away_team, $this->excluded_teams)) {
        continue;
      }
      $this->teams[$home_team]->addResult($result);
      $this->teams[$away_team]->addResult($result);
    }
  }

  public function build() {
    $results = $this->getResults();
    $this->teams = $this->getTeams($results);
    $this->recordResults($results);
  }

  public function getSortedTable() {
    if (count($this->teams) < 1) return "Table must be built first";
    $teams = array_values($this->teams);
    function sortByPoints($a, $b) {
      if ($b->points === $a->points) {
        return $b->getGoalDifference() - $a->getGoalDifference();
      }
      return $b->points - $a->points;
    }
    usort($teams, "sortByPoints");
    return $teams;
  }
}
?>
