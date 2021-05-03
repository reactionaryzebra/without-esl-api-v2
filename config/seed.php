<?php
include_once '../config/Database.php';
include_once '../models/result.php';

$database = new Database();
$db = $database->getConnection();

$create_table_query = "
DROP TABLE IF EXISTS results;
CREATE TABLE IF NOT EXISTS results (
  id serial NOT NULL PRIMARY KEY,
  season int NOT NULL,
  home_team varchar NOT NULL,
  away_team varchar NOT NULL,
  home_team_goals int NOT NULL,
  away_team_goals int NOT NULL,
  outcome varchar(1) NOT NULL
);";
$table = pg_query($db, $create_table_query);

$all_files = new DirectoryIterator('../data/');

foreach ($all_files as $file) {
  if ($file->isDot()) continue;
  $season = explode('.', explode('-', $file->getPathname())[1])[0];
  $data = file_get_contents($file->getPathname());
  $results = json_decode($data);
  foreach ($results as $_result) {
    $result = new Result($db);
    $result->season = $season;
    $result->home_team = $_result->HomeTeam;
    $result->away_team = $_result->AwayTeam;
    $result->home_team_goals = $_result->FTHG;
    $result->away_team_goals = $_result->FTAG;
    $result->outcome = $_result->FTR;
    $success = $result->create();
  }
}
 ?>
