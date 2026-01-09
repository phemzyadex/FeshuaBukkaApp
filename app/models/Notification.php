<?php
class Notification {
    public function sendSMS($to, $message) {
        $config = require '../config/sms.php';
        $sid = $config['sid'];
        $token = $config['token'];
        $from = $config['from'];

        $data = [
            'From' => $from,
            'To' => $to,
            'Body' => $message
        ];

        $ch = curl_init("https://api.twilio.com/2010-04-01/Accounts/$sid/Messages.json");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
        curl_setopt($ch, CURLOPT_USERPWD, "$sid:$token");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }

    public function sendEmail($to, $subject, $message) {
    $headers = "From: orders@fastfood.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    return mail($to, $subject, $message, $headers);
}

}
