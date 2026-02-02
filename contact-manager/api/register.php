<?php
/*
 * json format:
 * {
 *  firstName: "",
 *  lastName: "",
 *  email: "",
 *  username: "",
 *  password: ""
 * }
 */

declare(strict_types=1);

ini_set('display_errors', '0');
ini_set('log_errors', '1');
ini_set('error_log', __DIR__ . '/phpErrors.txt');
error_reporting(E_ALL);

$input = json_decode(
    file_get_contents('php://input'),
    true
);

header('Content-Type: application/json');

require './components/db.php';

if (!isset($username, $pass)){
    http_response_code(500);
    echo json_encode(["error" => "Username and password are required."]);
    exit;
}

function checkUsername(string $username):bool {
    if (strlen($username) < 3) return false;

    return !preg_match('/^[a-zA-Z0-9]/', $username);
}

function checkPassword(string $pass):bool {
    if (strlen($pass) < 3) return false;

    // decide on what characters should be in passwords.
    return true;
}

try{
    $username = $input["username"];
    $email = $input["email"];
    $firstName = $input["firstName"];
    $lastName = $input["lastName"];
    $password = $input["password"];

    // in case anyone sends a request themselves
    if (!checkUsername($username)){
        http_response_code(401);
        echo json_encode(["error" => "Invalid username."]);
        exit;
    }

    if (!checkPassword($password)){
        http_response_code(401);
        echo json_encode(["error" => "Invalid password."]);
        exit;
    }
    //

    $hash = password_hash($pass, PASSWORD_DEFAULT);

    // register account in db
    /** @var \PDO $pdo */
    $message = $pdo->prepare("INSERT INTO users (first_name, last_name, email, username, password_hash) VALUES (?, ?, ?, ?, ?,)");
    $message->execute([$firstName, $lastName, $email, $username, $hash]);

    $userId = $pdo->lastInsertId();

    require('./components/cookies.php');
    components\cookies\createCookie($userId);
    exit;
}catch (\PDOException $e){
    $errorCode = $e->getCode();
    http_response_code(500);
    switch ($errorCode) {
        case 23000:
            echo json_encode(["error" => "Username already taken."]);
            break;
        default:
            http_response_code(500);
    }
}