<?php

class VersionDetails{

    public $major_number = 0;
    public $minor_number = 0;
    public $patch_number = 0;
    public $build_number = 0;
    public $build_date = null;

    function __construct($composer_package_details)
    {
        $version_number = explode(".", $composer_package_details["version"]);

        $this->major_number = $version_number[0];
        $this->minor_number = $version_number[1];
        $this->patch_number = $version_number[2];
        $this->build_number = $version_number[3];
        $this->build_date = $composer_package_details["build_date"];
    }

    public function GetFullVersion()
    {
        return implode(".", [$this->major_number, $this->minor_number, $this->patch_number,$this->build_number]);
    }

    public function __toString(){
        return sprintf("%s (%s)", $this->GetFullVersion(), $this->build_date);
    }
}