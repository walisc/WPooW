<?php

include 'versionDetails.php';

$project_details = json_decode(file_get_contents("composer.json"), true); //This get run by composer, so needs to be relative to composer itself

$version_details = new VersionDetails($project_details);


$project_details["version"] = implode(".", [$version_details->major_number,
                                            $version_details->minor_number,
                                            $version_details->patch_number,
                                            $version_details->build_number+1]);

$project_details["build_date"] = date('Y-m-d H:i:s');


fwrite(fopen("composer.json", "w") , json_encode($project_details));

// Build Version
$build_project_details = [];
$build_project_details["name"] = $project_details["name"];
$build_project_details["description"] = $project_details["description"];
$build_project_details["license"] = $project_details["license"];
$build_project_details["type"] = $project_details["type"];
$build_project_details["version"] = $project_details["version"];
$build_project_details["build_date"] = $project_details["build_date"];

$build_directory = sprintf(".%s%s",DIRECTORY_SEPARATOR, "Build");

if (!file_exists($build_directory)) {
    mkdir($build_directory, 0777, true);
}
foreach(["Core", "Libraries"] as $cp_dir){
    RecursiveCopy(sprintf(".%s%s", DIRECTORY_SEPARATOR, $cp_dir), sprintf(".%s%s%s%s",DIRECTORY_SEPARATOR, $build_directory, DIRECTORY_SEPARATOR, $cp_dir));
}

foreach(["wpAPI.php", "LICENSE"] as $cp_file){
    copy($cp_file, sprintf("%s%s%s", $build_directory , DIRECTORY_SEPARATOR , $cp_file)); 
}

fwrite(fopen(sprintf("%s%s%s", $build_directory, DIRECTORY_SEPARATOR, "composer.json"), "w") , json_encode($build_project_details));


function RecursiveCopy($src,$dst) { 
    $dir = opendir($src); 
    @mkdir($dst); 
    while(false !== ( $file = readdir($dir)) ) { 
        if (( $file != '.' ) && ( $file != '..' )) { 
            if ( is_dir($src . '/' . $file) ) { 
                RecursiveCopy($src . '/' . $file,$dst . '/' . $file); 
            } 
            else { 
                copy($src . '/' . $file,$dst . '/' . $file); 
            } 
        } 
    } 
    closedir($dir); 
} 

$rootPath = realpath($build_directory);

$zip = new ZipArchive();
$zip->open('Build.zip', ZipArchive::CREATE | ZipArchive::OVERWRITE);

$files = new RecursiveIteratorIterator(
    new RecursiveDirectoryIterator($rootPath),
    RecursiveIteratorIterator::LEAVES_ONLY
);

foreach ($files as $name => $file)
{
    if (!$file->isDir())
    {
        $filePath = $file->getRealPath();
        $relativePath = substr($filePath, strlen($rootPath) + 1);
        $zip->addFile($filePath, $relativePath);
    }
}
$zip->close();

rmdir($build_directory);