<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/09/24
 * Time: 11:49 PM
 */
ini_set('phar.readonly',"off");

$phar = new Phar('Build/wpAPI.phar', 0, 'wpAPI.phar');
$phar->buildFromDirectory( getcwd());
$phar->setStub($phar->createDefaultStub('wpAPI.php'));