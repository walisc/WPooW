<?php

echo "--- Staring WPooW Tests --- ";

$ch=curl_init('http://localhost/wpoow_2_0/wp-admin/admin-ajax.php');
curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
curl_setopt($ch, CURLOPT_POSTFIELDS, "action=wpoow_testing_request");

$result = curl_exec($ch);

$resultDic = json_decode(substr($result,0,strlen($result)-1), true);
curl_close($ch);

if (!$resultDic["WPooWLinked"]){
    Logger::INFO("WPooW project not linked to WPooW test Plugin. Linking now");
    $testPluginPath =sprintf("%s%s",$resultDic["pluginPathDir"], DIRECTORY_SEPARATOR);
    $testPluginDetails = json_decode(file_get_contents($testPluginPath. "composer.sample.json"), true);
    $testPluginDetails["repositories"][0]["url"] = realpath(sprintf("%s%s%s",__DIR__, DIRECTORY_SEPARATOR, "../"));

    fwrite(fopen($testPluginPath."composer.json", "w") , json_encode($testPluginDetails));
    echo "\n";
    exec("composer install -d ". $resultDic["pluginPathDir"]);
    Logger::INFO("WPooW project linked successfully");

}



Class Logger{

    static function INFO($msg){
        echo sprintf("\n\033[32m %s INFO:\033[0m %s",  date("Y-m-d H:i:s"), $msg); 
    }
    static function WARN($msg){
        echo sprintf("\n\033[33m %s INFO:\033[0m %s",  date("Y-m-d H:i:s"), $msg); 
    }
    static function ERROR($msg){
        echo sprintf("\n\033[31m %s INFO:\033[0m %s",  date("Y-m-d H:i:s"), $msg);     
    }
    static function FATAL($msg){
        echo sprintf("\n\033[31m %s INFO:\033[0m %s",  date("Y-m-d H:i:s"), $msg);     
    }

}