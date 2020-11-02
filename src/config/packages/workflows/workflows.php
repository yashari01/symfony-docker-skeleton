<?php
declare(strict_types=1);

$worflows  = [];
$directory = new DirectoryIterator(__DIR__);
foreach ($directory as $file) {
    if ($file->isDot() || $file->getBasename() === 'workflows.php' || $file->getExtension() !== 'php') {
        continue;
    }

    $worflows[str_replace('.'.$file->getExtension(), '', $file->getBasename())] = include $file->getRealPath();
}

$container->loadFromExtension('framework', ['workflows' => $worflows]);