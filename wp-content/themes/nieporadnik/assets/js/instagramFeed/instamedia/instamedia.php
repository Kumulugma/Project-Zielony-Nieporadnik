<?php

include_once __DIR__ . DIRECTORY_SEPARATOR . 'init.php';

$url = isset($_GET['media-url']) ? $_GET['media-url'] : null;

if (empty($url) || !preg_match('/^https?:\/\/[a-zA-Z0-9\.-]+?\.cdninstagram\.com\//', $url, $matches))
{
    throw new \Exception();
}

$without_query = explode('?', $url)[0];
$ext = pathinfo($without_query, PATHINFO_EXTENSION);

// whitelist
if (!in_array(strtolower($ext), ['png', 'gif', 'mp4', 'jpg', 'jpeg'])) {
    throw new \Exception();
}

$file_name = md5($url).'.'.$ext;
$abs_file = generate_temp_path($file_name);

switch ($ext) {
    case 'png':
        header('content-type: image/png');
        break;
    case 'gif':
        header('content-type: image/gif');
        break;
    case 'mp4':
        header('content-type: video/mp4');
        break;
    default:
        header('content-type: image/jpeg');
        break;
}

if (file_exists($abs_file))
{
    echo file_get_contents($abs_file);
}
else
{
    $content = file_get_contents($url);
    file_put_contents($abs_file, $content);
    echo $content;
}