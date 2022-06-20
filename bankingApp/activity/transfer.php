<?php
    
    function getUserName($transferName)   {
        global $conn;
        $sqlQuery = "SELECT user_id FROM accounts WHERE account_id='$transferName'";
        $result = $conn->query($sqlQuery)->fetch_assoc();
        $sqlQuery = "SELECT name FROM users WHERE user_id='".$result['user_id']."'";
        $result = $conn->query($sqlQuery)->fetch_assoc();
        return $result['name'];
    }

    $data = file_get_contents('php://input');
    $json_user = json_decode($data, true);
    $accountId = $json_user['accountId'];
    $receiverAccountId = $json_user['transferAccountId'];
    $amount = $json_user['amount'];
    $transactionId = "";
    $transactionMessage = $json_user['transactionMessage'];
    date_default_timezone_set("Asia/Calcutta");

    $conn = new mysqli('localhost', 'root', '9159916916', 'banking_app');
    $sqlQuery = "SELECT account_balance FROM accounts WHERE account_id='$accountId'";
    $result = $conn->query($sqlQuery)->fetch_assoc();
    $accountBalance = $result['account_balance'];
    if ($accountBalance > $amount)  {
        $accountBalance = $accountBalance - $amount;
        $sqlQuery = "UPDATE accounts SET account_balance = '$accountBalance' WHERE account_id = $accountId";
        if ($conn->query($sqlQuery))    {
            $transactionId = substr($accountId, -4) + date("dmYHis");
            $sqlQuery = "INSERT INTO transactions (transaction_id, account_id, transaction_type, transfer_name, debit, account_balance, transaction_message) VALUES ($transactionId, $accountId, 'Sent', $receiverAccountId, $amount, $accountBalance, '$transactionMessage')";
            if (!($conn->query($sqlQuery)))    {
                $transactionId = "";
            }
            else    {
                $sqlQuery = "SELECT account_balance FROM accounts WHERE account_id='$receiverAccountId'";
                $result = $conn->query($sqlQuery)->fetch_assoc();
                $receiverAccountBalance = $result['account_balance'];
                $receiverAccountBalance = $receiverAccountBalance + $amount;
                $sqlQuery = "UPDATE accounts SET account_balance = $receiverAccountBalance WHERE account_id = $receiverAccountId";
                $execution = $conn->query($sqlQuery);
            }
        }
    }
    if (strlen($transactionId) != 0)   {
        $sqlQuery = "SELECT * FROM transactions WHERE transaction_id = '$transactionId'";
        $row = $conn->query($sqlQuery)->fetch_assoc();
        $credit = ($row['credit'] == "0") ? "" : $row['credit']."\$";
        $debit = ($row['debit'] == "0") ? "" : $row['debit']."\$";
        $transferName = ($row['transfer_name'] == "") ? "!!!!" : getUserName($row['transfer_name']);
        $transactionMessage = ($row['transaction_message'] == "") ? "!!!!" : $row['transaction_message'];
        $transactionRow = $row['transaction_id']."\$".$row['transaction_date']."\$".$row['transaction_type']."\$".$transferName."\$".$credit.$debit.$row['account_balance']."\$".$transactionMessage;
        $response = array("transaction_row"=>$transactionRow);
    }
    else    {
        $response = array("transaction_row"=>"-1");
    }

    echo json_encode($response);

?>