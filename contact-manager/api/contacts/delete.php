<?php

/*  DELETE sql query
*
*   $sql = "
*   DELETE FROM contacts
*   WHERE id = ? AND user_id = ?;
*    ";
*/
// Requirements
use function components\cookies\checkCookie;
require '../components/db.php';
header("Content-Type: application/json; charset=utf-8");

// Decode incoming data
$req_data = json_decode(file_get_contents('php://input'), true);
$user_token = $_COOKIE['authentication'];
$uid = checkCookie($pdo, $user_token);

try{
    $delete_query = $pdo->prepare("DELETE FROM contacts WHERE id=? AND user_id=?");
    $delete_query->bindParam(1, $req_data['contact_id'], PDO::PARAM_INT);
    $delete_query->bindParam(2, $uid, PDO::PARAM_INT);
    $delete_query->execute();
    echo json_encode([
        "query" => "ok"
    ]);
}
// DB Error
catch(PDOException $e){
    http_response_code(500);    
    echo json_encode([
        "query" => "error",
        "error" => "Database Error"
    ]);
}
// Other Error
catch(Exception $e){
    http_response_code(500);    
    echo json_encode([
        "query" => "error",
        "error" => "Unknown error"
    ]);
}