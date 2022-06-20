<?php

$data = file_get_contents('php://input');
$json_user = json_decode($data, true);
$email = $json_user['email'];
$password = $json_user['password'];

$conn = new mysqli('localhost', 'root', '9159916916', 'banking_app');
$sqlQuery = "SELECT name FROM users WHERE email='$email'";
$result = $conn->query($sqlQuery);
if ($result->num_rows > 0)  {
    $sqlQuery = "SELECT user_id, name FROM users WHERE email='$email' AND password='$password'";
    $result = $conn->query($sqlQuery);
    if ($result->num_rows == 1) {
            $reply = $result->fetch_assoc();
            $user_id = $reply['user_id'];
            $sqlQuery = "SELECT account_id FROM accounts WHERE user_id='$user_id'";
            $account_row = $conn->query($sqlQuery)->fetch_assoc();
            $response = array('account_id'=>$account_row['account_id'], 'name'=>$reply['name']);
    } else {
        $response = array('account_id'=>'-2');
    }
} else {
    $response = array('account_id'=>'-1');
}

echo json_encode($response);

?>