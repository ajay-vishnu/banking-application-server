<?php
    date_default_timezone_set("Asia/Calcutta");
    $timezone = date_default_timezone_get();
    echo "The current server timezone is: " . $timezone . "\n";
    echo "The current server time is: " . date('d-m-Y H:i:s') . "\n";
    echo "The time that can be used for account ids is: " . date('dmyHis');
?>