<?php


echo "-- Initializing WPooW tests --";

$ch=curl_init('http://localhost/wpoow_2_0/wp-admin/admin-ajax.php');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=wpoow_testing_request");

$result = curl_exec($ch);

curl_close($ch);