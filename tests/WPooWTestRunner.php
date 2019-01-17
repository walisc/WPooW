<?php

echo "--- Staring WPooW Tests --- ";

// 1. Linking files if need be

$result = ProcessRequest('http://localhost/wpoow_2_0/wp-admin/admin-ajax.php');
$resultDic = json_decode(substr($result,0,strlen($result)-1), true);

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

// 2. installing Selenium dependencies
if (!file_exists("./bin"))
{
    mkdir("./bin");
}

if (!file_exists("./bin/seleniumServer.jar")){
    Logger::INFO("Selenium Server is not installed. Installing now");
    ProcessFileRequest("http://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar",
                        fopen(sprintf("%s%s%s%s%s",__DIR__, DIRECTORY_SEPARATOR, "bin", DIRECTORY_SEPARATOR, "seleniumServer.jar"), "w+"));
}

if (!file_exists("./bin/chromeDrivers.zip")){
    Logger::INFO("Installing Selenium Chrome drivers");

    $dirveType = "";
    if (stristr(PHP_OS, 'DAR')){

    }else if (stristr(PHP_OS, 'WIN')){

    }else if(stristr(PHP_OS, 'LINUX')){

    }
    ProcessFileRequest("http://selenium-release.storage.googleapis.com/3.8/selenium-server-standalone-3.8.1.jar",
                        fopen(sprintf("%s%s%s%s%s",__DIR__, DIRECTORY_SEPARATOR, "bin", DIRECTORY_SEPARATOR, "seleniumServer.jar"), "w+"));
}

///wget https://chromedriver.storage.googleapis.com/2.34/chromedriver_linux64.zip
//unzip https://chromedriver.storage.googleapis.com/2.34/chromedriver_linux64.zip
//sudo mv -i chromedriver /usr/bin/.


// Helper functions

function ProcessFileRequest($url, $filePath){
    $filePath = fopen(sprintf("%s%s%s",__DIR__, DIRECTORY_SEPARATOR, "seleniumServer.jar"), "w+");
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_FILE, $filePath);
    curl_exec($ch);
    curl_close($ch);
}

function ProcessRequest($url){
    $ch=curl_init('http://localhost/wpoow_2_0/wp-admin/admin-ajax.php');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER , true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, "action=wpoow_testing_request");
    
    $result = curl_exec($ch);
    curl_close($ch);
    return $result;
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