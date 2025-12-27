<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$files = glob(TEMP_FOLDER . DIRECTORY_SEPARATOR."*");
$now   = time();

@mkdir(TEMP_FOLDER, 0755, true);

foreach ($files as $file) {
    if (is_file($file)) {
        if ($now - filemtime($file) >= (60 * 60 * 24 * 15)) { // 2 days
            unlink($file);
        }
    }
}