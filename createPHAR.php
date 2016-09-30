<?php
/**
 * Created by PhpStorm.
 * User: chidow
 * Date: 2016/09/24
 * Time: 11:49 PM
 */
ini_set('phar.readonly',"off");

// Based on http://stackoverflow.com/a/20501275
class MyRecursiveFilterIterator extends RecursiveFilterIterator {

    public static $FILTERS = array(
        '.svn',
    );

    public function accept() {
        return !in_array(
            $this->current()->getFilename(),
            self::$FILTERS,
            true
        );
    }

}

$exclude = array('.git', 'Docs', 'Build');

$filter = function ($file, $key, $iterator) use ($exclude) {
    if ($iterator->hasChildren() && !in_array($file->getFilename(), $exclude)) {
        return true;
    }
    return $file->isFile();
};

$innerIterator = new RecursiveDirectoryIterator(
    getcwd(),
    RecursiveDirectoryIterator::SKIP_DOTS
);
$iterator = new RecursiveIteratorIterator(
    new RecursiveCallbackFilterIterator($innerIterator, $filter)
);


unlink('Build/wpAPI.phar');

$phar = new Phar('Build/wpAPI.phar', 0, 'wpAPI.phar');
$phar->buildFromIterator(
    new RecursiveIteratorIterator(
        new RecursiveCallbackFilterIterator($innerIterator, $filter)),
    getcwd());
$phar->setStub($phar->createDefaultStub('cli/index.php', 'www/index.php'));