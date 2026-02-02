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

require '../components/db.php';
header('Content-Type: application/json');

# all if the user isnt actually searching (gets first 10 results) or contact_search if the user is actually searching
$search_type = 'all';
$offset = 0;
$limit = 10;
$uid = 1;

try{
    $search = $pdo->prepare("SELECT * FROM contacts WHERE user_id=? LIMIT ? OFFSET ?");
    $search->execute([$uid, $limit, $offset]);
    $rows = $search->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode($rows);
}
catch(PDOException $e){
    echo json_encode([
        "error" => "Database error"
    ]);
}
catch(Exception $e){
    echo json_encode([
        "error" => "Unknown error"
    ]);
}