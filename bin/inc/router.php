<?php

$request_uri = $_SERVER['REQUEST_URI'];
$current_dir = getcwd();

if (strlen($request_uri) == 0) {
   $request_uri = '/';
}

$mode = 'http';

// error_log(print_r($_ENV, true));
// error_log(print_r($_SERVER, true));
error_log($mode);

$project_folders = array(
    $current_dir . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'commondocs',
    $current_dir . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'httpdocs'
);

foreach ($project_folders as $basepath) {
    error_log("Search file $basepath");
    if (file_exists($basepath . $request_uri)) {
        error_log('In commondocs');
        header('Content-type: ' . mime_content_type($basepath . $request_uri));
        $fh = fopen($basepath . $request_uri, 'r');
        fpassthru($fh);
        fclose($fh);
        return true;
    }
}

return false;
