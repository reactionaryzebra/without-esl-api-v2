<?php

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
        header('Access-Control-Allow-Origin: *');
        header('Access-Control-Allow-Methods: POST, GET, DELETE, PUT, PATCH, OPTIONS');
        header('Access-Control-Allow-Headers: token, Content-Type');
        header('Access-Control-Max-Age: 1728000');
        header('Content-Length: 0');
        header('Content-Type: text/plain');
        die();
    }
    
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include_once '../config/Database.php';
include_once '../models/Result.php';
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
