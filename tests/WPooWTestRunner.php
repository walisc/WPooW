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
$project_dir = realpath(sprintf("%s%s%s", __DIR__, DIRECTORY_SEPARATOR, "../"));
$phpunitloc = sprintf("%s%s%s%s%s%s%s", $project_dir, DIRECTORY_SEPARATOR, "vendor", DIRECTORY_SEPARATOR, "bin", DIRECTORY_SEPARATOR, "phpunit" );
$binPath = sprintf("%s%s%s",__DIR__, DIRECTORY_SEPARATOR, "bin");
$driverPath = sprintf("%s%s%s", $binPath, DIRECTORY_SEPARATOR, "chromeDrivers.zip");
$seleniumPath = sprintf("%s%s%s", $binPath, DIRECTORY_SEPARATOR, "seleniumServer.jar");

$seleniumUrl = "http://selenium-release.storage.googleapis.com/3.9/selenium-server-standalone-3.9.1.jar";
$driverUrl = "https://chromedriver.storage.googleapis.com/2.45/chromedriver_%s.zip";

if (!file_exists($binPath))
{
    mkdir($binPath);
}

if (!file_exists($seleniumPath)){
    Logger::INFO("Selenium Server is not installed. Installing now");
    ProcessFileRequest($seleniumUrl, fopen($seleniumPath, "w+"));
}


if (!file_exists($driverPath)){
    Logger::INFO("Installing Selenium Chrome drivers");

    $dirveType = "";
    if (stristr(PHP_OS, 'DAR')){
        ProcessFileRequest(sprintf($driverUrl,"mac64"),fopen($driverPath, "w+"));
    }else if (stristr(PHP_OS, 'WIN')){
        ProcessFileRequest(sprintf($driverUrl,"win32"), fopen($driverPath, "w+"));
    }else if(stristr(PHP_OS, 'LINUX')){
        ProcessFileRequest(sprintf($driverUrl,"linux64"),fopen($driverPath, "w+"));
    }
    
    $zip = new ZipArchive;
    $res = $zip->open($driverPath);
    $zip->extractTo($binPath);
    $zip->close();

    
}
Logger::INFO("Setting Enviroment variables");
putenv('PATH=' . getenv('PATH') . PATH_SEPARATOR . $binPath);

# 3. Starting Selenium
#TODO check if already running
Logger::INFO("---- Starting Selenium----- \n\n");

$fp = fsockopen("localhost",4444, $errno, $errstr,1);
echo $errno;
if($errno != 0){   
    Logger::INFO("Launching Selenium Server");
    //Runs in the same process as opposes to system, that runs it in a different session
    exec(sprintf("java -jar %s -role node -servlet org.openqa.grid.web.servlet.LifecycleServlet -registerCycle 0 -port 4444  > /dev/null 2>&1 &", $seleniumPath));   
} else {
    Logger::INFO("Selenium already running");
} 
fclose($fp);


Logger::INFO("---- Running WPooW Tests---- \n\n");
system($phpunitloc);

#java -jar selenium-server-standalone-3.0.1.jar -role node -servlet org.openqa.grid.web.servlet.LifecycleServlet -registerCycle 0 -port 4444
#curl -s http://localhost:4444/extra/LifecycleServlet?action=shutdown


########## Helper Functions ##########
function ProcessFileRequest($url, $filePath){

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