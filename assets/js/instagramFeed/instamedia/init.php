<?php

define('TEMP_FOLDER', $base = __DIR__ . DIRECTORY_SEPARATOR . 'temp');

function generate_temp_path($file_name) {
    @mkdir(TEMP_FOLDER, 0775, true);

    return TEMP_FOLDER . DIRECTORY_SEPARATOR . str_replace('/', '-',str_replace(' ', '-', $file_name));
}