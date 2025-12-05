<?php

use CodeIgniter\Boot;
use Config\Paths;

$minPhpVersion = '8.1';
if (version_compare(PHP_VERSION, $minPhpVersion, '<')) {
    echo "PHP version must be {$minPhpVersion} or higher.";
    exit(1);
}

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

require FCPATH . '/../app/Config/Paths.php';

$paths = new Paths();

require FCPATH . '/../vendor/codeigniter4/framework/system/Boot.php';

exit(Boot::bootWeb($paths));
