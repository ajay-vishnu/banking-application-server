<?php

use function PHPSTORM_META\sql_injection_subst;

$data = file_get_contents('php://input');
$json_user = json_decode($data, true);
$email = $json_user['email'];
$name = $json_user['name'];
$password = $json_user['password'];
date_default_timezone_set("Asia/Calcutta");

$conn = new mysqli('localhost', 'root', '9159916916', 'banking_app');
$sqlQuery = "SELECT name FROM users WHERE email='$email'";
if ($conn->query($sqlQuery)->num_rows < 1)    {
    $sqlQuery = "INSERT INTO users (email, name, password) VALUES ('$email', '$name', '$password')";
    $execution = $conn->query($sqlQuery);
    if ($execution === True) {
        $sqlQuery = "SELECT user_id FROM users WHERE email='$email' AND password='$password'";
        $reply = $conn->query($sqlQuery)->fetch_assoc();
        $currentTime = date("dmYHis");
        $user_id = $reply['user_id'];
        $sqlQuery = "INSERT INTO accounts (user_id, account_id, account_balance) VALUES ($user_id, $currentTime, 0.0)";
        $conn->query($sqlQuery);
        $sqlQuery = "SELECT account_id FROM accounts WHERE user_id='$user_id'";
        $reply = $conn->query($sqlQuery)->fetch_assoc();
        $response = array('account_id'=>$reply['account_id']);
    } else {
        $response = array('account_id'=>'-2');
    }
} else {
    $response = array('account_id'=>'-1');
}

echo json_encode($response);

?>
