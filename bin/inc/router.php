<?php

$request_uri = $_SERVER['REQUEST_URI'];
$current_dir = getcwd();

if (strlen($request_uri) == 0) {
   $request_uri = '/';
}

error_log(print_r($_SERVER, true));
error_log(ini_get('include_path'));
error_log(ini_get('open_basedir'));

$request_file = preg_replace('/\//', DIRECTORY_SEPARATOR, $request_uri);

$mode = 'http';

$project_folders = array(
    $current_dir . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'commondocs',
    $current_dir . DIRECTORY_SEPARATOR . 'pub' . DIRECTORY_SEPARATOR . 'httpdocs'
);

foreach ($project_folders as $basepath) {
    $filename = $basepath . $request_file;
    if (file_exists($filename) && is_file($filename)) {
        error_log('In commondocs');
        header('Content-type: ' . mime_content_type($filename));
        $fh = fopen($filename, 'r');
        fpassthru($fh);
        fclose($fh);
        return true;
    }
}

error_log('Calling nabu-3 loader');
$_GET['__x_nb_path'] = $request_file;
include $project_folders[1] . DIRECTORY_SEPARATOR . 'nabu-3.php';

return true;
