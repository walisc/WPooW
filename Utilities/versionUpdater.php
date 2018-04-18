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

fwrite(fopen("./Build/project.json", "w") , json_encode($build_project_details));

