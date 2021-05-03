<?php
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Headers: *");
header("Access-Control-Allow-Methods: POST");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../models/result.php';
include_once '../models/Team.php';
include_once '../models/Table.php';

// instantiate database and product object
$database = new Database();
$db = $database->getConnection();

//Convert params and declare as variables
$params = json_decode(file_get_contents('php://input'), TRUE);
$season = $params['season'];
$excluded_teams = $params['excludedTeams'];

// Build and sort the table
$table = new Table($db, $season, $excluded_teams);
$table->build();
$answer = $table->getSortedTable();

// return a response
http_response_code(200);
echo json_encode($answer);

?>
