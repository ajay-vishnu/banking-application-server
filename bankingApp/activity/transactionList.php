<?php

    $data = file_get_contents('php://input');
    $json_user = json_decode($data, true);
    $accountId = $json_user['accountId'];
    date_default_timezone_set("Asia/Calcutta");

    $conn = new mysqli('localhost', 'root', '9159916916', 'banking_app');
    $transactionList = "";
    $sqlQuery = "SELECT * FROM transactions WHERE account_id='$accountId' OR transfer_name='$accountId'";
    $result = $conn->query($sqlQuery);
    if ($result->num_rows > 0)  {
        while($row = $result->fetch_assoc())    {
            if ($accountId = $row['transfer_name']) {
                $credit = $row['debit'];
                $transferName = getUserName($row['account_id']);
                $transactionMessage = ($row['transaction_message'] == "") ? "!!!!" : $row['transaction_message'];
                $transactionList = "$transactionList#".$row['transaction_id']."\$".$row['transaction_date']."\$Received\$".$transferName."\$".$credit."\$".$row['account_balance']."\$".$transactionMessage;
            }
            else    {
                $credit = ($row['credit'] == "0") ? "" : $row['credit']."\$";
                $debit = ($row['debit'] == "0") ? "" : $row['debit']."\$";
                $transferName = ($row['transfer_name'] == "") ? "!!!!" : getUserName($row['transfer_name']);
                $transactionMessage = ($row['transaction_message'] == "") ? "!!!!" : $row['transaction_message'];
                $transactionList = "$transactionList#".$row['transaction_id']."\$".$row['transaction_date']."\$".$row['transaction_type']."\$".$transferName."\$".$credit.$debit.$row['account_balance']."\$".$transactionMessage;
            }
        }
    }
    
    function getUserName($transferName)   {
        global $conn;
        $sqlQuery = "SELECT user_id FROM accounts WHERE account_id='$transferName'";
        $result = $conn->query($sqlQuery)->fetch_assoc();
        $sqlQuery = "SELECT name FROM users WHERE user_id='".$result['user_id']."'";
        $result = $conn->query($sqlQuery)->fetch_assoc();
        return $result['name'];
    }

    $response = array("transaction_list"=>$transactionList);
    
    echo json_encode($response);

?>