<?php

/*  Search sql query
*   
*   $sql = "
*   SELECT id, user_id, first_name, last_name, email, personal_phone, work_phone, date_created
*   FROM contacts
*   WHERE user_id = ? AND (first_name LIKE ? OR last_name LIKE ?)
*   ORDER BY first_name, last_name;
*    ";
*/

use function components\cookies\checkCookie;

require '../components/db.php';
header("Content-Type: application/json; charset=utf-8");
$req_data = json_decode(file_get_contents('php://input'), true);

//$user_token = $_COOKIE['authentication'];
$searchName = "%" . $req_data['search_query'] . "%";
$offset = ($req_data['pagination'] - 1) * 10;
$limit = 10;
$uid = 1;//checkCookie($pdo, $user_token);

try{
    $search = $pdo->prepare("SELECT id,first_name,last_name,email,personal_phone,work_phone FROM contacts WHERE user_id= ? AND (first_name LIKE ? OR last_name LIKE ?) LIMIT ?, ?");
    $search->bindValue(1, $uid, PDO::PARAM_INT);
    $search->bindValue(2, $searchName, PDO::PARAM_STR);
    $search->bindValue(3, $searchName, PDO::PARAM_STR);
    $search->bindValue(4, $offset, PDO::PARAM_INT);
    $search->bindValue(5, $limit, PDO::PARAM_INT);
    $search->execute();
    
    $total_contact_count_search = $pdo->prepare("SELECT COUNT(*) FROM contacts WHERE user_id= ?");
    $total_contact_count_search->bindValue(1, $uid, PDO::PARAM_INT);
    $total_contact_count_search->execute();
    $total_contact_count = $total_contact_count_search->fetchColumn();
    
    
    $rows = $search->fetchAll(PDO::FETCH_ASSOC);
    $response = [
        "query" => "ok",
        "contacts" => $rows,
        "total_contact_count" => $total_contact_count   
    ];
    
    echo json_encode($response);
}
catch(PDOException $e){
    echo json_encode([
        "query" => "error",
        "error" => "Database Error"
    ]);
}
catch(Exception $e){
    echo json_encode([
        "query" => "error",
        "error" => "Unknown error"
    ]);
}