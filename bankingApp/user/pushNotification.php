<?php
    function pushNotification() {
        $URL = "https://fcm.googleapis.com/fcm/send";
        $API_KEY = "AAAApKvjzNE:APA91bGTKoN_MEinPF63RLRbmaxgESw0ALCJ8e1UQ5CGvjSAOeX2v13aR3mJj3dmve79DqLUMh-6UwyAH031xAHChA_Z_kyFb6ldMN4ctmUbMBTi1OffBtBu2I87ix47ZrOMSjg0qJnP";
        $HEADERS = array(
            'Authorization:key='.$API_KEY,
            'Content-Type:application/json'
        );
        $notificationData = [
            'title' => 'My new notification',
            'body' => 'My notification body'
        ];
        $dataPayload = [
            'to' => 'VIP',
            'date' => '2021-05-11',
            'other_data' => 'data kd;skfj'
        ];
    }
?>