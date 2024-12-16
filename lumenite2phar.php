<?php
try {
    $pharFile = 'lumenitephp.phar';

    $phar = new Phar($pharFile);

    $phar->startBuffering();

    $phar->buildFromDirectory('lumenitephp/'); // Path to your project files

    $phar->setStub($phar->createDefaultStub('lumenitephp')); // Replace 'cli' with your entry script

    // Stop buffering and finalize the .phar file
    $phar->stopBuffering();

    echo "PHAR archive '$pharFile' created successfully!";
} catch (Exception $e) {
    echo 'Error: ', $e->getMessage();
}
